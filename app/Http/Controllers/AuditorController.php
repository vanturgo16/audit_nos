<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

//model
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use App\Models\MstGrading;
use App\Models\MstPeriodeChecklists;
use App\Models\User;
use App\Models\ChecklistJaringan;

// Mail
use App\Mail\SubmitChecklist;
use App\Models\ChecklistResponses;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\MailingTrait;

class AuditorController extends Controller
{
    use AuditLogsTrait;
    use MailingTrait;

    public function startChecklist($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            $checklist = ChecklistJaringan::findOrFail($id);
            // Lock the row for update to prevent race condition
            $period = MstPeriodeChecklists::where('id', $checklist->id_periode)->lockForUpdate()->firstOrFail();
            // If already claimed by another auditor, abort
            if (!is_null($period->id_auditor) && $period->id_auditor !== auth()->id()) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Checklist already claimed by another auditor.']);
            }
            // Assign current user if not yet assigned
            if (is_null($period->id_auditor)) {
                $period->update(['id_auditor' => auth()->id()]);
            }
            $checklist->update([
                'status' => '0',
                'start_date' => now(),
            ]);

            //Audit Log
            $this->auditLogsShort('Start Checklist :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Start Checklist']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Start Checklist!']);
        }
    }

    public function submitChecklist($id, Request $request)
    {
        $id = decrypt($id); //id_periode

        // MAILING
        // [ INITIATE VARIABLE ] 
        $variableEmail = $this->variableEmail();
        $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type', DB::raw('(SELECT COUNT(*) FROM mst_assign_checklists WHERE mst_assign_checklists.id_periode_checklist = mst_periode_checklists.id) as totalChecklist'))
            ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
        // Group By Type Checklist For Create Paper Assign (Checklist Jaringan)
        $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
        $checklistdetail = ChecklistJaringan::where('id_periode', $id)
            ->orderByRaw("FIELD(type_checklist, '" . $sortOrder->implode("','") . "')")
            ->get();
        // Recepient Email
        $toemail = $ccemail = null;
        if ($variableEmail['devRule'] == 1) {
            $toemail = $variableEmail['emailDev'];
        } else {
            $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
            $ccemail = $variableEmail['emailSubmitter'];
        }
        // Mail Structure
        $mailStructure = new SubmitChecklist($periodInfo, $checklistdetail, $variableEmail['emailSubmitter']);

        DB::beginTransaction();
        try {
            $checkJars = ChecklistJaringan::where('id_periode', $id)->whereNotIn('status', [1, 5])->get();
            foreach ($checkJars as $item) {
                // Get Audit Result
                $auditResult = MstGrading::where('bottom', '<=', $item->result_percentage)
                    ->where('top', '>=', $item->result_percentage)
                    ->value('result') ?? 'Bronze';
                // Get Mandatory ITEM
                $mandatoryCounts = ChecklistResponses::selectRaw('
                    SUM(mst_assign_checklists.ms = 1 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as sgp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as gp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 0 AND mst_assign_checklists.mp = 1) as p
                ')
                    ->join('mst_assign_checklists', 'checklist_responses.id_assign_checklist', '=', 'mst_assign_checklists.id')
                    ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', '=', 'mst_periode_checklists.id')
                    ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                    ->where('checklist_responses.response', '!=', 'Exist, Good')
                    ->where('mst_periode_checklists.id', $id)
                    ->first();
                if ((int)$mandatoryCounts->sgp > 0) {
                    $mandatoryItem = 'Bronze';
                } elseif ((int)$mandatoryCounts->gp > 0) {
                    $mandatoryItem = 'Silver';
                } elseif ((int)$mandatoryCounts->p > 0) {
                    $mandatoryItem = 'Gold';
                } else {
                    $mandatoryItem = 'Platinum';
                }
                // Get Result Final
                $priority = [
                    'Bronze' => 1,
                    'Silver' => 2,
                    'Gold' => 3,
                    'Platinum' => 4
                ];
                $resultFinal = array_search(min($priority[$auditResult], $priority[$mandatoryItem]), $priority);

                ChecklistJaringan::where('id', $item->id)->update([
                    'status' => 2,
                    'last_decision_assessor' => 0,
                    'audit_result' => $auditResult,
                    'mandatory_item' => $mandatoryItem,
                    'result_final' => $resultFinal
                ]);
            }
            // IF After Revision Reset Rejected For Review Again
            MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 2)->update(['approve' => null]);
            // Update Status
            MstPeriodeChecklists::where('id', $id)->update(['status' => 3]);

            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            //Log Period
            $this->storeLogPeriod($id, 3, 'Submit Audit Checklist');
            //Audit Log
            $this->auditLogsShort('Submit answer Checklist Period (' . $id . ')');

            DB::commit();
            return redirect()->route('listassigned.periodList')->with(['success' => 'Success Submit Your Answer Checklist']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Submit Checklist!']);
        }
    }
}

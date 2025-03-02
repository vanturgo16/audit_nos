<?php

namespace App\Http\Controllers;

use App\Models\ChecklistResponse;
use App\Models\FileInputResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

//model
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use App\Models\MstEmployees;
use App\Models\MstGrading;
use App\Models\MstJaringan;
use App\Models\MstParentChecklists;
use App\Models\MstPeriodeChecklists;
use App\Models\TransFileResponse;
use App\Models\MstRules;
use App\Models\User;
use App\Models\ChecklistJaringan;
use Carbon\Carbon;

// Mail
use App\Mail\SubmitChecklist;
use App\Models\ChecklistResponses;
use Mockery\Undefined;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\MailingTrait;

class AuditorController extends Controller
{
    use AuditLogsTrait;
    use MailingTrait;

    public function periodList(Request $request)
    {
        $idJaringan = MstEmployees::where('email', auth()->user()->email)->first()->id_dealer;
        $jaringanDetail = MstJaringan::where('id', $idJaringan)->first();

        $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->whereNotNull('mst_periode_checklists.status')
            ->where('mst_periode_checklists.status', '!=', 0)
            ->where('mst_periode_checklists.id_branch', $idJaringan)
            ->orderBy('mst_periode_checklists.created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('action', function ($data) {
                    return view('auditor.period.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Assign Period Checklist Auditor');

        return view('auditor.period.index', compact('jaringanDetail'));
    }

    public function periodDetail(Request $request, $id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();

        $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
        $checkJars = ChecklistJaringan::where('id_periode', $id)->orderByRaw("FIELD(type_checklist, '" . $sortOrder->implode("','") . "')")->get();
        foreach ($checkJars as $item) {
            $responsCounts = ChecklistResponses::join('mst_assign_checklists', 'checklist_responses.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                ->where('mst_periode_checklists.id', $id)
                ->groupBy('checklist_responses.response')
                ->selectRaw('checklist_responses.response as type_response, COUNT(*) as count')
                ->get()->toArray();
            $item->point = $responsCounts;

            if(in_array($item->type_checklist, $typeChecklistPerCheck)) {
                $isComplete = ($item->checklist_remaining == 0) ? 1 : 0;
            } else {
                if ($item->checklist_remaining == 0) {
                    $isComplete = ChecklistResponses::join('mst_assign_checklists', 'checklist_responses.id_assign_checklist', 'mst_assign_checklists.id')
                        ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                        ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                        ->where('mst_periode_checklists.id', $id)
                        ->whereNull('checklist_responses.path_input_response')
                        ->exists() ? 0 : 1;
                } else {
                    $isComplete = 0;
                }
            }
            $item->isComplete = $isComplete;
        }

        $allComplete = $checkJars->contains(function ($item) {
            return $item->isComplete === 0;
        }) ? 0 : 1;

        if ($request->ajax()) {
            $statusPeriod = $periodInfo->is_active;
            $startPeriod = $periodInfo->start_date;
            $today = Carbon::today()->format('Y-m-d');

            return DataTables::of($checkJars)
                ->addColumn('action', function ($data) use ($statusPeriod, $startPeriod, $today) {
                    return view('auditor.period.detail.action', compact('data', 'statusPeriod', 'startPeriod', 'today'));
                })
                ->toJson();
        }

        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('auditor.period.detail.index', compact('id', 'periodInfo', 'allComplete'));
    }

    public function startChecklist($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            ChecklistJaringan::where('id', $id)->update([
                'status' => '0',
                'start_date' => Carbon::now(),
            ]);

            //Audit Log
            $this->auditLogsShort('Start Checklist :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Start Checklist']);
        } catch (\Exception $e) {
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
        if ($variableEmail['devRule'] == 1) {
            $toemail = $variableEmail['emailDev'];
            $ccemail = null;
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
            return redirect()->route('auditor.periodList')->with(['success' => 'Success Submit Your Answer Checklist']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Submit Checklist!']);
        }
    }

    public function detailChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $chekJar = ChecklistJaringan::where('id', $id)->first();
        $period = MstPeriodeChecklists::where('id', $chekJar->id_periode)->first();
        $typeCheck = $chekJar->type_checklist;
        $assignChecks = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.path_input_response')
            ->leftjoin('checklist_responses', 'mst_assign_checklists.id', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id_periode_checklist', $chekJar->id_periode)
            ->where('mst_assign_checklists.type_checklist', $chekJar->type_checklist)
            ->orderby('mst_assign_checklists.order_no_parent')
            ->orderby('mst_assign_checklists.order_no_checklist')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($assignChecks)
                ->addColumn('file', function ($data) {
                    return view('review.file', compact('data'));
                })
                ->addColumn('detail', function ($data) {
                    return view('review.detail', compact('data'));
                })
                ->addColumn('photo', function ($data) {
                    return view('review.photo', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View Review Checklist');

        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $view = in_array($typeCheck, $typeChecklistPerCheck) ? 'auditor.detail.index-h1' : 'auditor.detail.index-other';
        return view($view, compact('id', 'chekJar', 'assignChecks', 'period', 'typeCheck'));
    }
}

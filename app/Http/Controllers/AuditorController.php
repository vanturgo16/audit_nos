<?php

namespace App\Http\Controllers;

use App\Models\ChecklistResponse;
use App\Models\FileInputResponse;
use App\Models\MstAssignChecklists;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

//model
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

class AuditorController extends Controller
{
    use AuditLogsTrait;

    public function periodList(Request $request)
    {
        $branchs = MstJaringan::get();
        $branchName = null;

        $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->whereNotNull('mst_periode_checklists.status')
            ->where('mst_periode_checklists.status', '!=', 0);

        $role = auth()->user()->role;
        if (in_array($role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC Dealers', 'PIC NOS MD'])) {
            if ($request->has('filterBranch') && $request->filterBranch != '' && $request->filterBranch != 'All') {
                $query->where('mst_periode_checklists.id_branch', $request->filterBranch);
            }
        } else {
            $idBranch = MstEmployees::where('email', auth()->user()->email)->first()->id_dealer;
            $branchName = MstJaringan::where('id', $idBranch)->first()->dealer_name;
            $query->where('mst_periode_checklists.id_branch', $idBranch);
        }

        $query = $query->orderBy('mst_periode_checklists.created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('action', function ($data) use ($branchs) {
                    return view('auditor.period.action', compact('data', 'branchs'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Assign Period Checklist Auditor');

        return view('auditor.period.index', compact('branchs', 'branchName'));
    }

    public function periodDetail(Request $request, $id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();

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

            if ($item->type_checklist == 'H1 Premises') {
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
            $item->reviewed = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNull('approve')->exists()) ? 0 : 1;
        }

        $allComplete = $checkJars->contains(function ($item) {
            return $item->isComplete === 0;
        }) ? 0 : 1;

        $allReviewed = $checkJars->contains(function ($item) {
            return $item->reviewed === 0;
        }) ? 0 : 1;

        if ($request->ajax()) {
            $statusPeriod = $periodInfo->is_active;
            $startPeriod = $periodInfo->start_date;
            $today = Carbon::today()->format('Y-m-d');

            return DataTables::of($checkJars)
                ->addColumn('action', function ($data) use ($statusPeriod, $startPeriod, $today) {
                    return view('auditor.detail.action', compact('data', 'statusPeriod', 'startPeriod', 'today'));
                })
                ->toJson();
        }

        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('auditor.detail.index', compact('id', 'periodInfo', 'allComplete', 'allReviewed'));
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

        DB::beginTransaction();
        try {
            $checkJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 1)->get();
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
                if ($mandatoryCounts->sgp != null) {
                    $mandatoryItem = 'Bronze';
                } elseif ($mandatoryCounts->gp != null) {
                    $mandatoryItem = 'Silver';
                } elseif ($mandatoryCounts->p != null) {
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
            MstPeriodeChecklists::where('id', $id)->update(['status' => 3]);

            // // [ MAILING ]
            // // Initiate Variable
            // $emailsubmitter = auth()->user()->email;
            // $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            // $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            //     ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            //     ->where('mst_periode_checklists.id', $id)
            //     ->first();
            // $count = MstAssignChecklists::where('id_periode_checklist', $id)->count();
            // $periodinfo->count = $count;
            // $checklistdetail = ChecklistJaringan::where('id_periode', $id)->get();
            // // Recepient Email
            // if ($development == 1) {
            //     $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
            //     $ccemail = null;
            // } else {
            //     $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
            //     $ccemail = $emailsubmitter;
            // }
            // // Mail Content
            // $mailInstance = new SubmitChecklist($periodinfo, $checklistdetail, $emailsubmitter);
            // // Send Email
            // Mail::to($toemail)->cc($ccemail)->send($mailInstance);

            $this->auditLogsShort('Submit answer Checklist Period (' . $id . ')');

            DB::commit();
            return redirect()->route('auditor.periodList')->with(['success' => 'Success Submit Your Answer Checklist']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Submit Checklist!']);
        }
    }
}

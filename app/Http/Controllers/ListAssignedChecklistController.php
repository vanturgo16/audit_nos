<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;
use App\Models\ChecklistJaringan;
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use App\Models\ChecklistResponses;
use App\Models\MstEmployees;
use App\Models\LogActivityPeriod;
use App\Models\MstRules;

// Trait
use App\Traits\AuditLogsTrait;

class ListAssignedChecklistController extends Controller
{
    use AuditLogsTrait;

    public function periodList(Request $request)
    {
        $user = auth()->user();
        $role = $user->role;
        $idDealerUser = MstEmployees::where('email', $user->email)->value('id_dealer');

        // Get branches based on role
        $branchs = in_array($role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC NOS MD'])
            ? MstJaringan::all()
            : MstJaringan::where('id', MstEmployees::where('email', $user->email)->value('id_dealer'))->get();

        if ($request->ajax()) {
            $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type',
                'a.id as idAuditor', 'a.name as auditor_name')
                ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->leftjoin('users as a', 'mst_periode_checklists.id_auditor', 'a.id')
                ->whereNotNull('mst_periode_checklists.status')
                ->where('mst_periode_checklists.status', '!=', 0);
    
            if(in_array($role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC NOS MD'])){
                if ($request->filled('filterBranch') && $request->filterBranch !== 'All') {
                    $query->where('mst_periode_checklists.id_branch', $request->filterBranch);
                }
            } else {
                $query->where('mst_periode_checklists.id_branch', $branchs->first()->id);
            }

            $query = $query->orderBy('mst_periode_checklists.created_at', 'desc')->get();

            return DataTables::of($query)
                ->addColumn('action', function ($data) use ($branchs, $idDealerUser) {
                    return view('list_assigned.action', compact('data', 'branchs', 'idDealerUser'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Assign Period Checklist Auditor');

        return view('list_assigned.index', compact('branchs'));
    }

    public function periodDetail(Request $request, $id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type',
            'users.id as idAuditor', 'users.name as auditor_name')
            ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
            ->leftjoin('users', 'mst_periode_checklists.id_auditor', 'users.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
            
        $user = auth()->user();
        $isHisDealer = MstEmployees::where('email', $user->email)->value('id_dealer') === $periodInfo->id_branch;

        $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
        $checkJars = ChecklistJaringan::select('checklist_jaringan.*', 'users.name as assesor_name')
            ->leftjoin('users', 'checklist_jaringan.id_assesor', 'users.id')
            ->where('checklist_jaringan.id_periode', $id)
            ->orderByRaw("FIELD(type_checklist, '" . $sortOrder->implode("','") . "')")
            ->get();

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

            // Correction
            $responsCountCorrections = ChecklistResponses::join('mst_assign_checklists', 'checklist_responses.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                ->where('mst_periode_checklists.id', $id)
                ->groupBy('checklist_responses.response_correction')
                ->selectRaw('checklist_responses.response_correction as type_response, COUNT(*) as count')
                ->get()->toArray();
            $item->point_correction = $responsCountCorrections;

            $item->reviewed = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNull('approve')->exists()) ? 0 : 1;
        }

        $allCompleteCheck = $checkJars->every(function ($item) {
            return $item->checklist_remaining == 0;
        }) ? 1 : 0;

        $allReviewedAssesor = $checkJars->contains(function ($item) {
            return $item->reviewed === 0;
        }) ? 0 : 1;
        $isCorrection = $checkJars->contains(function ($item) {
            return $item->last_correction_assessor === 0;
        }) ? 1 : 0;
        $allReviewedPIC = $checkJars->contains(function ($item) {
            return $item->last_decision_pic === 0;
        }) ? 0 : 1;

        $idAssesors = $checkJars->pluck('id_assesor')->filter()->toArray();

        if ($request->ajax()) {
            $statusPeriod = $periodInfo->is_active;
            $startPeriod = $periodInfo->start_date;
            $today = Carbon::today()->format('Y-m-d');

            $idAuditor = $periodInfo->id_auditor;
            return DataTables::of($checkJars)
                ->addColumn('action', function ($data) use ($statusPeriod, $startPeriod, $today, $idAuditor, $isHisDealer) {
                    return view('list_assigned.detail.action', compact('data', 'statusPeriod', 'startPeriod', 'today', 'idAuditor', 'isHisDealer'));
                })
                ->toJson();
        }

        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('list_assigned.detail.index', compact('id', 'periodInfo', 'allCompleteCheck', 'allReviewedAssesor', 'isCorrection', 'allReviewedPIC', 'idAssesors'));
    }

    public function detailChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $checkJar = ChecklistJaringan::where('id', $id)->first();
        $period = MstPeriodeChecklists::where('id', $checkJar->id_periode)->first();
        $typeCheck = $checkJar->type_checklist;

        $assignChecks = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.response_correction', 'checklist_responses.path_input_response')
            ->leftjoin('checklist_responses', 'mst_assign_checklists.id', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id_periode_checklist', $checkJar->id_periode)
            ->where('mst_assign_checklists.type_checklist', $checkJar->type_checklist)
            ->orderby('mst_assign_checklists.order_no_parent')
            ->orderby('mst_assign_checklists.order_no_checklist')
            ->get();

        // Total count
        $totalCount = $assignChecks->count();
        // Count where approve is null
        $reviewedCount = $assignChecks->whereNotNull('approve')->count();
        $progressReviewed = $reviewedCount.'/'.$totalCount;

        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $perCheck = in_array($typeCheck, $typeChecklistPerCheck) ? true : false;

        //Audit Log
        $this->auditLogsShort('View Review Checklist');
        
        return view('list_assigned.detail.checklist.index', compact('checkJar', 'period', 'typeCheck', 'assignChecks', 'perCheck', 'progressReviewed'));
    }

    public function logActivity(Request $request, $id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::where('id', $id)->first();
        $datas = LogActivityPeriod::where('id_period', $id)->orderby('created_at', 'desc')->get();

        //Audit Log
        $this->auditLogsShort('View List Log Activity Period ' . $id);

        return view('list_assigned.detail.logactivity', compact('periodInfo', 'datas'));
    }
}

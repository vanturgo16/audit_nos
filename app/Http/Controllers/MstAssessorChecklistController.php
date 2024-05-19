<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;
use App\Models\SubmitReviewLog;
use App\Models\MstRules;
use App\Models\User;
use App\Models\ChecklistJaringan;
use App\Models\ChecklistResponse;
use App\Models\FileInputResponse;
use App\Models\FinishReviewLog;
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use App\Models\MstGrading;

// Mail
use App\Mail\SubmitReviewChecklist;
use App\Mail\SubmitPICReviewChecklist;

class MstAssessorChecklistController extends Controller
{
    use AuditLogsTrait;

    public function listjaringan(Request $request)
    {
        if ($request->ajax()) {
            $query = MstJaringan::get();

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('assessor.listjaringan.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Jaringan in Assessor Checklist');
        
        return view('assessor.listjaringan.index');
    }

    public function listperiod(Request $request, $id)
    {
        $id = decrypt($id);

        $jaringan = MstJaringan::where('id', $id)->first();

        if ($request->ajax()) {
            $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->orderBy('mst_periode_checklists.created_at')
                ->where('mst_dealers.id', $id)
                ->where('mst_periode_checklists.is_active', '1')
                ->get();

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('assessor.listperiod.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Period Jaringan '.$jaringan->dealer_name.' in Assessor Checklist');
        
        return view('assessor.listperiod.index', compact('jaringan'));
    }

    public function typechecklist(Request $request, $id)
    {
        $id = decrypt($id);

        // Check Has History or not
        $historydecision = FinishReviewLog::where('id_period', $id)->first();

        // Get Period
        $period = MstPeriodeChecklists::where('id', $id)->first();

        $id_jaringan = MstPeriodeChecklists::where('id', $id)->first()->id_branch;
        
        // Check The Checklist Has Full Review or no
        $checks = ChecklistJaringan::select('status')->where('id_periode', $id)->get();
        $check = 0;
        foreach ($checks as $checkItem) {
            if ($checkItem->status == 2 || $period->status == 5 || $period->status == 4 || $period->status == 6) {
                $check = 1;
                break;
            }
        }

        if ($request->ajax()) {
            //ForSortingBasedDropdown
            $sortdropdown = MstDropdowns::where('category', 'Type Checklist')->orderby('created_at')->pluck('name_value')->toArray();
            $datas = ChecklistJaringan::where('id_periode', $id)->orderByRaw("FIELD(type_checklist, '" . implode("','", $sortdropdown) . "')")->get();

            foreach($datas as $data){
                $responsCounts = ChecklistResponse::select('checklist_response.response as response')
                    ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                    ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                    ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                    ->where('mst_assign_checklists.type_checklist', $data->type_checklist)
                    ->where('mst_periode_checklists.id', $id)
                    ->groupBy('checklist_response.response')
                    ->selectRaw('checklist_response.response as type_response, COUNT(*) as count')
                    ->get()->toArray();
                $data->point = $responsCounts;
            }

            foreach($datas as $datam){
                $mandatoryCounts = ChecklistResponse::selectRaw('
                    SUM(mst_assign_checklists.ms = 1 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as sgp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as gp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 0 AND mst_assign_checklists.mp = 1) as p
                ')
                ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_assign_checklists.type_checklist', $data->type_checklist)
                ->whereNot('checklist_response.response', 'Exist, Good')
                ->where('mst_periode_checklists.id', $id)
                ->get()->toArray();
                $datam->mandatory = $mandatoryCounts;
            }

            $grading = MstGrading::all();
            $status = $datas->every(function ($item, $key) {
                return $item['status'] == 1;
            });

            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) {
                return view('assessor.typechecklist.action', compact('data'));
            })
            ->addColumn('resultbutton', function ($data) use ($grading) {
                return view('assessor.typechecklist.result', compact('data', 'grading'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist Period: '.$period->periode.' in Assessor Checklist');
        
        return view('assessor.typechecklist.index', compact('period', 'check', 'historydecision'));
    }

    public function review(Request $request, $id)
    {
        $id = decrypt($id);
        
        $type = ChecklistJaringan::where('id', $id)->first();

        //file point
        $file_point = FileInputResponse::select(
            'file_input_response.*',
            'trans_file_response.parent_point'
        )
        ->Join('trans_file_response', 'file_input_response.id_trans_file', 'trans_file_response.id')
        ->Join('mst_periode_checklists', 'trans_file_response.id_period', 'mst_periode_checklists.id')
        ->where('trans_file_response.type_checklist', $type->type_checklist)
        ->where('mst_periode_checklists.id', $type->id_periode)
        ->get();

        // dd($file_point);

        if ($request->ajax()) {
            $query = MstAssignChecklists::select(
                'mst_assign_checklists.id as id_assign', 
                'mst_assign_checklists.id_periode_checklist as id_periode_checklist', 
                'mst_assign_checklists.*',
                'mst_assign_checklists.path_guide_parent as path_guide_premises', 
                'mst_assign_checklists.parent_point_checklist as parent_point', 
                'mst_assign_checklists.id_mst_checklist as id_checklist', 
                'checklist_jaringan.type_checklist',
            )
            ->Join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
            ->Join('checklist_jaringan', 'mst_assign_checklists.id_periode_checklist', 'checklist_jaringan.id_periode')
            ->where('checklist_jaringan.id', $id)
            ->where('mst_assign_checklists.type_checklist', $type->type_checklist)
            ->get();

            foreach ($query as $q) {
                $response = ChecklistResponse::where('id_assign_checklist', $q->id_assign)->first();
                $q->response = $response->response;
                $q->path_input_response = $response->path_input_response;
            }

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($file_point) {
                return view('assessor.review.action', compact('data', 'file_point'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('Review in Assessor Checklist');
        
        return view('assessor.review.index', compact('type'));
    }

    public function submitreview(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all(), $id);

        if($request->decision == 'Approved'){
            $status = 6;
            $reason = null;
        } else {
            $request->validate([
                'reason' => 'required',
            ]);
            $status = 4;
            $reason = $request->reason;
        }

        DB::beginTransaction();
        try{
            
            $update = ChecklistJaringan::where('id', $id)->where('type_checklist', $request->typechecklist)->update([
                'status' => $status,
                'last_decision' => $request->decision,
                'last_reason' => $reason,
            ]);

            //Audit Log
            $this->auditLogsShort('Submit Review Checklist - id : '.$id.', Type: '.$request->typechecklist);

            DB::commit();
            return redirect()->route('assessor.typechecklist', encrypt($request->idperiod))->with(['success' => 'Success Submit Decission']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Submit Checklist!']);
        }
    }

    public function finishreview(Request $request, $id)
    {
        $id = decrypt($id);

        $userEmail = auth()->user()->email;

        //Get Status For Period, (Check IF Inside Period Checklist, The Checklist Contain Not Approved)
        $statuschecklist = ChecklistJaringan::select('status', 'id')->where('id_periode', $id)->get();
        $status = 4;
        foreach ($statuschecklist as $checkItem) {
            if ($checkItem->status == 4) {
                $status = 5;
                break;
            }
        }

        DB::beginTransaction();
        try{

            //disini harus update status checklist jaringan kalau statusnya masih 3
            foreach($statuschecklist as $updatecheck){
                if ($updatecheck->status == 4) {
                    ChecklistJaringan::where('id', $updatecheck->id)->update([
                        'status' => '5'
                    ]);
                }
                if ($updatecheck->status == 6) {
                    ChecklistJaringan::where('id', $updatecheck->id)->update([
                        'status' => '7'
                    ]);
                }
            }
            // Create Finish Review Log
            $log_finish = FinishReviewLog::create([
                'id_period' => $id,
                'date' => now(),
                'status' => $status,
                'note' => $request->note,
                'finish_by' => $userEmail,
            ]);

            // Get Data Checklist
            $listchecklist = ChecklistJaringan::where('id_periode', $id)->get();

            // Create Submit Log From Finish Review
            foreach($listchecklist as $check){
                SubmitReviewLog::create([
                    'id_finish_review' => $log_finish->id,
                    'type_checklist' => $check->type_checklist,
                    'date' => $log_finish->updated_at,
                    'decision' => $check->last_decision,
                    'reason' => $check->last_reason,
                ]);
            }

            // Update Period Status
            $update = MstPeriodeChecklists::where('id', $id)->update([
                'status' => $status,
                'decisionpic' => null
            ]);
            
            // [ MAILING ]
            // Initiate Variable
            $emailsubmitter = auth()->user()->email;
            $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_periode_checklists.id', $id)
                ->first();
            $count = MstAssignChecklists::where('id_periode_checklist', $id)->count();
            $periodinfo->count = $count;
            $checklistdetail = ChecklistJaringan::where('id_periode', $id)->get();
            $note = $request->note;
            // Recepient Email
            if($development == 1){
                $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                $ccemail = null;
            } else {
                $internalaudit = MstPeriodeChecklists::leftJoin('mst_employees', 'mst_periode_checklists.id_branch', 'mst_employees.id_dealer')
                    ->leftJoin('users', 'mst_employees.email', 'users.email')
                    ->where('mst_periode_checklists.id', $id)
                    ->where('users.role', 'Internal Auditor Dealer')
                    ->pluck('mst_employees.email')->toArray();
                $picnosmd = User::where('role', 'PIC NOS MD')->pluck('email')->toArray();
                $toemail = array_merge($internalaudit, $picnosmd);
                $ccemail = $emailsubmitter;
            }
            // Mail Content
            $mailInstance = new SubmitReviewChecklist($periodinfo, $checklistdetail, $emailsubmitter, $note);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailInstance);

            // Check Expired Date Period if Not Approved
            if($status == 5){
                $period = MstPeriodeChecklists::where('id', $id)->first();
                if ($period) {
                    $today = Carbon::today();
                    if ($period->end_date <= $today) {
                        MstPeriodeChecklists::where('id', $id)->update(['status' => null]);
                    }
                }
            }

            //Audit Log
            $this->auditLogsShort('Finish Review Checklist Id Period: '.$id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Finish Review']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Finish Checklist!']);
        }
    }

    public function closedapproved(Request $request, $id)
    {
        $id = decrypt($id);

        if($request->decision == "Approved"){
            $status = 6;
            $decisionpic = 1;
        } else {
            $status = 5;
            $decisionpic = 0;
        }

        DB::beginTransaction();
        try{
            // Create Finish Review Log
            $log_finish = FinishReviewLog::create([
                'id_period' => $id,
                'date' => now(),
                'status' => $status,
                'note' => $request->note,
                'finish_by' => auth()->user()->email,
            ]);

            // Update Period Status
            $update = MstPeriodeChecklists::where('id', $id)->update([
                'status' => $status,
                'decisionpic' => $decisionpic,
                'notespic' => $request->note
            ]);

            // Update Checklist Jaringan to Reject All If Reject
            if($status == 5){
                ChecklistJaringan::where('id_periode', $id)->update([
                    'status' => '5'
                ]);
            }

            // Send Email Decision
            // [ MAILING ]
            // Initiate Variable
            $emailsubmitter = auth()->user()->email;
            $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_periode_checklists.id', $id)
                ->first();
            $count = MstAssignChecklists::where('id_periode_checklist', $id)->count();
            $periodinfo->count = $count;
            $checklistdetail = ChecklistJaringan::where('id_periode', $id)->get();
            $note = $request->note;
            // Recepient Email
            if($development == 1){
                $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                $ccemail = null;
            } else {
                $auditor = MstPeriodeChecklists::leftJoin('mst_employees', 'mst_periode_checklists.id_branch', 'mst_employees.id_dealer')
                    ->leftJoin('users', 'mst_employees.email', 'users.email')
                    ->where('mst_periode_checklists.id', $id)
                    ->where('users.role', 'Internal Auditor Dealer')
                    ->pluck('mst_employees.email')->toArray();
                $assessor = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                $toemail = array_merge($auditor, $assessor);
                $ccemail = auth()->user()->email;
            }
            // Mail Content
            $mailInstance = new SubmitPICReviewChecklist($periodinfo, $checklistdetail, $emailsubmitter, $note);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailInstance);

            // Check Expired Date Period
            $period = MstPeriodeChecklists::where('id', $id)->first();
            if ($period) {
                $today = Carbon::today();
                if ($period->end_date <= $today) {
                    MstPeriodeChecklists::where('id', $id)->update(['status' => null]);
                }
            }

            //Audit Log
            $this->auditLogsShort('Decision PIC Checklist Id Period: '.$id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Decision Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Submit Decision Checklist!']);
        }
    }

    public function history(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($id);

        $period = MstPeriodeChecklists::where('id', $id)->first();
        $datas = FinishReviewLog::where('id_period', $id)->get();

        //ForSortingBasedDropdown
        $sortdropdown = MstDropdowns::where('category', 'Type Checklist')->orderby('created_at')->pluck('name_value')->toArray();
        
        foreach($datas as $data){
            $submitlog = SubmitReviewLog::where('id_finish_review', $data->id)->orderByRaw("FIELD(type_checklist, '" . implode("','", $sortdropdown) . "')")->get();
            $data->submitlog = $submitlog;
        }

        if ($request->ajax()) {
            $query = FinishReviewLog::where('id_period', $id)->get();

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('assessor.history.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List History Decision Period '.$period->period.' in Assessor Checklist');
        
        return view('assessor.history.index', compact('period', 'datas'));
    }
}

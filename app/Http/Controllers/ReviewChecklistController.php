<?php

namespace App\Http\Controllers;

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
use App\Models\ChecklistResponses;
use App\Models\MstEmployees;

// Mail
use App\Mail\SubmitReviewChecklist;
use App\Mail\SubmitPICReviewChecklist;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\MailingTrait;

class ReviewChecklistController extends Controller
{
    use AuditLogsTrait;
    use MailingTrait;

    public function periodList(Request $request)
    {
        $branchs = MstJaringan::get();

        $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->whereNotNull('mst_periode_checklists.status')
            ->where('mst_periode_checklists.status', '!=', 0);

        if ($request->has('filterBranch') && $request->filterBranch != '' && $request->filterBranch != 'All') {
            $query->where('mst_periode_checklists.id_branch', $request->filterBranch);
        }

        $query = $query->orderBy('mst_periode_checklists.created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('action', function ($data) use ($branchs) {
                    return view('review.period.action', compact('data', 'branchs'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Assign Period Checklist Auditor');

        return view('review.period.index', compact('branchs'));
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
            $item->reviewed = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNull('approve')->exists()) ? 0 : 1;
        }

        $allReviewed = $checkJars->contains(function ($item) {
            return $item->reviewed === 0;
        }) ? 0 : 1;

        $allReviewedPIC = $checkJars->contains(function ($item) {
            return $item->last_decision_pic === 0;
        }) ? 0 : 1;

        if ($request->ajax()) {
            $statusPeriod = $periodInfo->is_active;
            return DataTables::of($checkJars)
                ->addColumn('action', function ($data) use ($statusPeriod) {
                    return view('review.period.detail.action', compact('data', 'statusPeriod'));
                })
                ->toJson();
        }

        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('review.period.detail.index', compact('id', 'periodInfo', 'allReviewed', 'allReviewedPIC'));
    }

    public function reviewChecklist(Request $request, $id)
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
                })
                ->addColumn('action', function ($data) use ($chekJar) {
                    return view('review.action', compact('data', 'chekJar'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View Review Checklist');

        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $view = in_array($typeCheck, $typeChecklistPerCheck) ? 'review.index-h1' : 'review.index-other';
        return view($view, compact('id', 'assignChecks', 'period', 'typeCheck', 'chekJar'));
    }

    // REVIEW ASSESSOR
    public function decisionChecklist(Request $request)
    {
        $decision = $request->decision;
        if ($decision === null) {
            // Handle reset case
            MstAssignChecklists::where('id', $request->id)->update(['approve' => null]);
        } else {
            // Handle approve/reject case
            MstAssignChecklists::where('id', $request->id)->update(['approve' => $decision]);
        }
        return response()->json(['success' => true]);
    }
    public function updateNoteChecklist(Request $request, $id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            ChecklistJaringan::where('id', $id)->update([
                'last_reason_assessor' => $request->note
            ]);

            //Audit Log
            $this->auditLogsShort('Assessor Update Note Type Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Update Note']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Update Note!']);
        }
    }
    public function submitReviewChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $nextStatus = (MstAssignChecklists::where('id_periode_checklist', $id)->whereNotIn('approve', [1, 3])->exists()) ? 2 : 4;
        $chekJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 5)->get();
        foreach ($chekJars as $item) {
            $item->isApprove = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNotIn('approve', [1, 3])->exists()) ? 0 : 1;
        }

        DB::beginTransaction();
        try {
            // Update Period
            MstPeriodeChecklists::where('id', $id)->update(['status' => $nextStatus]);
            // Update Checklist Jaringan
            foreach ($chekJars as $item) {
                if ($nextStatus == 4) {
                    ChecklistJaringan::where('id', $item->id)->update(['status' => 3, 'last_decision_assessor' => 2, 'last_decision_pic' => 0]);
                } else {
                    if ($item->isApprove == 1) {
                        ChecklistJaringan::where('id', $item->id)->update(['status' => 1, 'last_decision_assessor' => 2]);
                    } else {
                        ChecklistJaringan::where('id', $item->id)->update(['status' => 4, 'last_decision_assessor' => 1]);
                    }
                }
            }
            // Update Assign Checklist
            MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 1)->update(['approve' => 3]);
            MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 0)->update(['approve' => 2]);

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

            $emailAuditor = MstEmployees::leftjoin('users', 'users.email', 'mst_employees.email')->where('mst_employees.id_dealer', $periodInfo->id_branch)->where('users.role', 'Internal Auditor Dealer')->pluck('users.email');
            if ($emailAuditor->isEmpty()) {
                return redirect()->back()->with(['fail' => 'Failed, Data Employee Internal Auditor Jaringan "' . $periodInfo->dealer_name . '" Not Exist']);
            }
            // Recepient Email
            if ($variableEmail['devRule'] == 1) {
                $toemail = $variableEmail['emailDev'];
                $ccemail = null;
            } else {
                // IF Reject Send Back To Internal Auditor
                if ($nextStatus == 2) {
                    $toemail = $emailAuditor;
                }
                // IF Approve Send To PIC NOS MD
                else {
                    $toemail = User::where('role', 'PIC NOS MD')->pluck('email')->toArray();
                }
                $ccemail = $variableEmail['emailSubmitter'];
            }
            // Mail Structure
            $mailStructure = new SubmitReviewChecklist($nextStatus, $periodInfo, $checklistdetail, $variableEmail['emailSubmitter'], $request->note);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            //Log Period
            $this->storeLogPeriod($id, $nextStatus, $request->note);
            //Audit Log
            $this->auditLogsShort('Assessor Submit Review Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Review']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Submit Review!']);
        }
    }

    // REVIEW PIC NOS MD
    public function updateDecisionPIC(Request $request, $id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            ChecklistJaringan::where('id', $id)->update([
                'last_decision_pic' => $request->decision,
                'last_reason_pic' => $request->note
            ]);

            //Audit Log
            $this->auditLogsShort('PIC NOS MD Update Decision Type Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->route('review.periodDetail', encrypt($request->idPeriod))->with(['success' => 'Success Update Decision']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Update Decision!']);
        }
    }
    public function submitPICReviewChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $chekJars = ChecklistJaringan::where('id_periode', $id)->get();
        $nextStatus = (ChecklistJaringan::where('id_periode', $id)->where('last_decision_pic', 1)->exists()) ? 3 : 5;

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
        $emailAuditor = MstEmployees::leftjoin('users', 'users.email', 'mst_employees.email')->where('mst_employees.id_dealer', $periodInfo->id_branch)->where('users.role', 'Internal Auditor Dealer')->pluck('users.email')->toArray();
        if (empty($emailAuditor)) {
            return redirect()->back()->with(['fail' => 'Failed, Data Employee Internal Auditor Jaringan "' . $periodInfo->dealer_name . '" Not Exist']);
        }
        // Recepient Email
        if ($variableEmail['devRule'] == 1) {
            $toemail = $variableEmail['emailDev'];
            $ccemail = null;
        } else {
            // IF Reject Send Back To Assessor Main Dealer
            if ($nextStatus == 3) {
                $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
            }
            // IF Approve Send To Internal Auditor & Assessor Main Dealer Information Done
            else {
                $assessorEmail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                $toemail = array_unique(array_merge($assessorEmail, $emailAuditor));
            }
            $ccemail = $variableEmail['emailSubmitter'];
        }
        // Mail Structure
        $mailStructure = new SubmitPICReviewChecklist($nextStatus, $periodInfo, $checklistdetail, $variableEmail['emailSubmitter'], $request->note);

        DB::beginTransaction();
        try {
            // Update Period
            MstPeriodeChecklists::where('id', $id)->update(['status' => $nextStatus]);

            // Update Checklist Jaringan
            foreach ($chekJars as $item) {
                // If Reject
                if ($item->last_decision_pic == 1) {
                    ChecklistJaringan::where('id', $item->id)->update(['status' => 2]);
                    // Update Assign Checklist
                    MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->update(['approve' => 1]);
                }
                // If Approve
                elseif ($item->last_decision_pic == 2) {
                    ChecklistJaringan::where('id', $item->id)->update(['status' => 5]);
                }
            }

            // SEND EMAIL
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            //Log Period
            $this->storeLogPeriod($id, $nextStatus, $request->note);
            //Audit Log
            $this->auditLogsShort('PIC NOS MD Submit Review Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Review']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Submit Review!']);
        }
    }
}

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

class ReviewChecklistController extends Controller
{
    use AuditLogsTrait;

    public function reviewChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $chekJar = ChecklistJaringan::where('id', $id)->first();
        $period = MstPeriodeChecklists::where('id', $chekJar->id_periode)->first();
        $typeCheck = $chekJar->type_checklist;
        $note = $chekJar->last_reason_assessor;
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
                ->addColumn('action', function ($data) {
                    return view('review.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View Review Checklist');

        $view = $typeCheck == 'H1 Premises' ? 'review.index-h1' : 'review.index-other';
        return view($view, compact('id', 'assignChecks', 'period', 'typeCheck', 'note'));
    }

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
        $chekJars = ChecklistJaringan::where('id_periode', $id)->get();
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
                    ChecklistJaringan::where('id', $item->id)->update(['status' => 3, 'last_decision_assessor' => 2]);
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

            //Audit Log
            $this->auditLogsShort('Assessor Submit Review Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Review']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Submit Review!']);
        }
    }
}

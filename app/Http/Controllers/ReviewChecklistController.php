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

    // public function reviewChecklistOld(Request $request, $id)
    // {
    //     $id = decrypt($id);
    //     $chekJar = ChecklistJaringan::where('id', $id)->first();
    //     $period = MstPeriodeChecklists::where('id', $chekJar->id_periode)->first();
    //     $typeCheck = $chekJar->type_checklist;
    //     $assignChecks = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.path_input_response')
    //         ->leftjoin('checklist_responses', 'mst_assign_checklists.id', 'checklist_responses.id_assign_checklist')
    //         ->where('mst_assign_checklists.id_periode_checklist', $chekJar->id_periode)
    //         ->where('mst_assign_checklists.type_checklist', $chekJar->type_checklist)
    //         ->orderby('mst_assign_checklists.order_no_parent')
    //         ->orderby('mst_assign_checklists.order_no_checklist')
    //         ->get();

    //         // dd($assignChecks);
    //         // dd($chekJar);

    //     if ($request->ajax()) {
    //         return DataTables::of($assignChecks)
    //             ->addColumn('file', function ($data) {
    //                 return view('review.file', compact('data'));
    //             })
    //             ->addColumn('detail', function ($data) {
    //                 return view('review.detail', compact('data'));
    //             })
    //             ->addColumn('photo', function ($data) {
    //                 return view('review.photo', compact('data'));
    //             })
    //             ->addColumn('action', function ($data) use ($chekJar) {
    //                 return view('review.action', compact('data', 'chekJar'));
    //             })->toJson();
    //     }

    //     //Audit Log
    //     $this->auditLogsShort('View Review Checklist');

    //     $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
    //     $view = in_array($typeCheck, $typeChecklistPerCheck) ? 'review.index-h1' : 'review.index-other';
    //     return view($view, compact('id', 'assignChecks', 'period', 'typeCheck', 'chekJar'));
    // }
    // // REVIEW ASSESSOR
    // public function decisionChecklist(Request $request)
    // {
    //     $decision = $request->decision;
    //     if ($decision === null) {
    //         // Handle reset case
    //         MstAssignChecklists::where('id', $request->id)->update(['approve' => null]);
    //     } else {
    //         // Handle approve/reject case
    //         MstAssignChecklists::where('id', $request->id)->update(['approve' => $decision]);
    //     }
    //     return response()->json(['success' => true]);
    // }

    // REVIEW ASSESSOR
    public function takeReview($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            $period = MstPeriodeChecklists::findOrFail($id);
            if (is_null($period->id_assesor)) {
                $period->update(['id_assesor' => auth()->id()]);
            } else {
                return redirect()->back()->with(['fail' => 'Has Any Other Assesor Take This Checklist for Review!']);
            }
            //Audit Log
            $this->auditLogsShort('Take Review Period Checklist ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Take Review This Checklist']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Take Review This Checklist!']);
        }
    }

    // DECISION ASSESSOR
    public function approve($id, Request $request) {
        $item = MstAssignChecklists::findOrFail($id);
        $item->approve = 1;
        $item->note_assesor = null;
        $item->save();
        return $this->returnChecklistCard($id, $request->index);
    }
    public function reject($id, Request $request) {
        $item = MstAssignChecklists::findOrFail($id);
        $item->approve = 0;
        $item->note_assesor = $request->note;
        $item->save();
        return $this->returnChecklistCard($id, $request->index);
    }
    public function reset($id, Request $request) {
        $item = MstAssignChecklists::findOrFail($id);
        $item->approve = null;
        // $item->note_assesor = null;
        $item->save();
        return $this->returnChecklistCard($id, $request->index);
    }
    public function correction($id, Request $request) {
        $item = MstAssignChecklists::findOrFail($id);
        $item->note_assesor = $request->note;
        $item->save();
        ChecklistResponses::where('id_assign_checklist', $id)->update(['response_correction' => $request->responseCorrection]);
        return $this->returnChecklistCard($id, $request->index);
    }
    
    public function renderCardOnly($id, Request $request) {
        return $this->returnChecklistCard($id, $request->index);
    }
    protected function returnChecklistCard($id, $index) {
        $updatedItem = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.response_correction', 'checklist_responses.path_input_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id', $id)
            ->first();
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $perCheck = in_array($updatedItem->type_checklist, $typeChecklistPerCheck) ? true : false;
        $statusCorrection = optional(ChecklistJaringan::where('id_periode', $updatedItem->id_periode_checklist)
            ->where('type_checklist', $updatedItem->type_checklist)
            ->first())->last_correction_assessor;
        $idAssesor = optional(MstPeriodeChecklists::where('id', $updatedItem->id_periode_checklist)
            ->first())->id_assesor;
        $assignChecks = MstAssignChecklists::where('id_periode_checklist', $updatedItem->id_periode_checklist)
            ->where('type_checklist', $updatedItem->type_checklist)
            ->get();
        // Total count
        $totalCount = $assignChecks->count();
        // Count where approve is null
        $reviewedCount = $assignChecks->whereNotNull('approve')->count();
        $progressReviewed = $reviewedCount.'/'.$totalCount;

        return response()->json([
            'html' => view('review.card_item', [
                'item' => $updatedItem,
                'perCheck' => $perCheck,
                'statusCorrection' => $statusCorrection,
                'idAssesor' => $idAssesor,
                'index' => $index
            ])->render(),
            'progressReviewed' => $progressReviewed,
        ]);
    }
    public function syncResultCorrection($id)
    {
        $id = decrypt($id);
        $checkJar = ChecklistJaringan::where('id', $id)->first();
        $recalculate = $this->reCalculateAssesorResult($checkJar->id_periode, $checkJar->type_checklist);
        if ($recalculate['changed']) {
            return redirect()->back()->with(['success' => 'Result correction has been successfully updated.']);
        }
        return redirect()->back()->with(['info' => 'No changes detected in result correction.']);
    }
    public function reCalculateAssesorResult($idPeriod, $typeCheck)
    {
        $checked = $totalEG = $totalENG = $totalPointAssesor = $result = 0;

        // Step 1: Find checklist jaringan
        $checklistJaringan = ChecklistJaringan::where('id_periode', $idPeriod)
            ->where('type_checklist', $typeCheck)
            ->first();

        if (!$checklistJaringan) {
            return [
                'success' => false,
                'message' => 'Checklist jaringan not found.',
            ];
        }

        // Step 2: Get related responses
        $responses = ChecklistResponses::where('id_checklist_jaringan', $checklistJaringan->id)->get();

        $checked = $responses->count();
        $totalEG = $responses->where('response_correction', 'Exist, Good')->count();
        $totalENG = $responses->where('response_correction', 'Exist Not Good')->count();

        // Step 3: Scoring logic
        // H1 Premises = EG*1 + ENG*-1 + NE/NA*0
        // H23 Premises = EG*1 + ENG*-1 + NE/NA*0
        // H1 People = EG*2 + ENG*1 + NE/NA*0
        // H1 Process = EG*1 + ENG*0 + NE/NA*0
        // H23 People = EG*2 + ENG*1 + NE/NA*0
        // H23 Process = EG*1 + ENG*0 + NE/NA*0
        if (in_array($typeCheck, ['H1 People', 'H23 People'])) {
            $totalPointAssesor = ($totalEG * 2) + $totalENG;
            $denominator = 2 * $checked;
        } elseif (in_array($typeCheck, ['H1 Process', 'H23 Process'])) {
            $totalPointAssesor = $totalEG;
            $denominator = $checked;
        } else {
            $totalPointAssesor = $totalEG - $totalENG;
            $denominator = $checked;
        }

        $result = ($denominator > 0) ? ($totalPointAssesor / $denominator) * 100 : 0;
        $formattedResultAssesor = round($result, 2); // Float, not string

        // Step 4: Audit result based on grading
        $auditResultAssesor = MstGrading::where('bottom', '<=', $formattedResultAssesor)
            ->where('top', '>=', $formattedResultAssesor)
            ->value('result') ?? 'Bronze';

        // Step 5: Mandatory item grading
        $mandatoryCounts = ChecklistResponses::selectRaw('
            SUM(CASE WHEN mst_assign_checklists.ms = 1 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1 THEN 1 ELSE 0 END) as sgp,
            SUM(CASE WHEN mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1 THEN 1 ELSE 0 END) as gp,
            SUM(CASE WHEN mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 0 AND mst_assign_checklists.mp = 1 THEN 1 ELSE 0 END) as p
        ')
            ->join('mst_assign_checklists', 'checklist_responses.id_assign_checklist', '=', 'mst_assign_checklists.id')
            ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', '=', 'mst_periode_checklists.id')
            ->where('mst_assign_checklists.type_checklist', $typeCheck)
            ->where('checklist_responses.response_correction', '!=', 'Exist, Good')
            ->where('mst_periode_checklists.id', $idPeriod)
            ->first();

        if ((int) $mandatoryCounts->sgp > 0) {
            $mandatoryItemAssesor = 'Bronze';
        } elseif ((int) $mandatoryCounts->gp > 0) {
            $mandatoryItemAssesor = 'Silver';
        } elseif ((int) $mandatoryCounts->p > 0) {
            $mandatoryItemAssesor = 'Gold';
        } else {
            $mandatoryItemAssesor = 'Platinum';
        }

        // Step 6: Final result based on priority
        $priority = [
            'Bronze' => 1,
            'Silver' => 2,
            'Gold' => 3,
            'Platinum' => 4
        ];
        $resultFinalAssesor = array_search(
            min($priority[$auditResultAssesor], $priority[$mandatoryItemAssesor]),
            $priority
        );

        // Step 7: Update database
        $changed = (
            (float) $checklistJaringan->total_point_assesor !== (float) $totalPointAssesor ||
            (float) $checklistJaringan->result_percentage_assesor !== (float) $formattedResultAssesor ||
            $checklistJaringan->audit_result_assesor !== $auditResultAssesor ||
            $checklistJaringan->mandatory_item_assesor !== $mandatoryItemAssesor ||
            (int) $checklistJaringan->result_final_assesor !== (int) $resultFinalAssesor
        );
        if ($changed) {
            ChecklistJaringan::where('id', $checklistJaringan->id)->update([
                'total_point_assesor' => $totalPointAssesor,
                'result_percentage_assesor' => $formattedResultAssesor,
                'audit_result_assesor' => $auditResultAssesor,
                'mandatory_item_assesor' => $mandatoryItemAssesor,
                'result_final_assesor' => $resultFinalAssesor,
            ]);
        }

        // Step 8: Return values for debugging or confirmation
        return [
            'success' => true,
            'changed' => $changed,
            'total_point' => $totalPointAssesor,
            'percentage' => $formattedResultAssesor,
            'audit_result' => $auditResultAssesor,
            'mandatory_item' => $mandatoryItemAssesor,
            'final_result' => $resultFinalAssesor
        ];
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
        $nextStatus = (MstAssignChecklists::where('id_periode_checklist', $id)->whereNotIn('approve', [1, 3])->exists()) ? 2 : 3;
        // Except Done Checklist
        $chekJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 5)->get();
        
        DB::beginTransaction();
        try {
            $msgResponse = 'Success Submit Review';
            // IF ANY REVISI WILL THROW BACK TO AUDITOR
            if ($nextStatus == 2) {
                // Update Period
                MstPeriodeChecklists::where('id', $id)->update(['status' => $nextStatus]);
                foreach ($chekJars as $item) {
                    $isApprove = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNotIn('approve', [1, 3])->exists()) ? 0 : 1;
                    if ($isApprove == 1) {
                        ChecklistJaringan::where('id', $item->id)->update(['status' => 1, 'last_decision_assessor' => 2]);
                    } else {
                        ChecklistJaringan::where('id', $item->id)->update(['status' => 4, 'last_decision_assessor' => 1]);
                    }
                }
                $note = $request->note;

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
                $toemail = $ccemail = null;
                if ($variableEmail['devRule'] == 1) {
                    $toemail = $variableEmail['emailDev'];
                } else {
                    $toemail = $emailAuditor;
                }
                // Mail Structure
                $mailStructure = new SubmitReviewChecklist($nextStatus, $periodInfo, $checklistdetail, $variableEmail['emailSubmitter'], $request->note);
                // Send Email
                Mail::to($toemail)->cc($ccemail)->send($mailStructure);
            } 
            // IF APPROVE ALL, MOVE TO CORRECTION ASSESOR SECTION
            else {
                foreach ($chekJars as $item) {
                    ChecklistJaringan::where('id', $item->id)->update([
                        'status' => 2, 'last_decision_assessor' => 2, 'last_correction_assessor' => 0,
                        'total_point_assesor' => $item->total_point, 'result_percentage_assesor' => $item->result_percentage,
                        'audit_result_assesor' => $item->audit_result, 'mandatory_item_assesor' => $item->mandatory_item, 'result_final_assesor' => $item->result_final
                    ]);
                    // Copy Response Auditor To Response Assesor Init
                    $responses = ChecklistResponses::where('id_checklist_jaringan', $item->id)->get();
                    foreach ($responses as $response) {
                        ChecklistResponses::where('id', $response->id)->update([
                            'response_correction' => $response->response
                        ]);
                    }
                }
                $msgResponse = $note = 'Move To Correction Assesor Section';
            }

            // Update Assign Checklist
            MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 1)->update(['approve' => 3]);
            MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 0)->update(['approve' => 2]);

            //Log Period
            $this->storeLogPeriod($id, $nextStatus, $note);
            //Audit Log
            $this->auditLogsShort('Assessor Submit Review Checklist Jaringan :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => $msgResponse]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Submit Review!']);
        }
    }
    public function submitCorrectionChecklist(Request $request, $id)
    {
        $id = decrypt($id);
        $nextStatus = 4;
        $chekJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 5)->get();

        DB::beginTransaction();
        try {
            // Update Checklist Jaringan
            foreach ($chekJars as $item) {
                // Recalculate
                $this->reCalculateAssesorResult($id, $item->type_checklist);
                ChecklistJaringan::where('id', $item->id)->update(['status' => 3, 'last_correction_assessor' => 1, 'last_decision_pic' => 0]);
            }

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
                $toemail = User::where('role', 'PIC NOS MD')->pluck('email')->toArray();
                $ccemail = $variableEmail['emailSubmitter'];
            }
            // Mail Structure
            $mailStructure = new SubmitReviewChecklist($nextStatus, $periodInfo, $checklistdetail, $variableEmail['emailSubmitter'], $request->note);
            
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            // Update Period
            MstPeriodeChecklists::where('id', $id)->update(['status' => $nextStatus]);
            //Log Period
            $this->storeLogPeriod($id, $nextStatus, $request->note);
            //Audit Log
            $this->auditLogsShort('Assessor Submit Correction Period Checklist :' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Correction']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['fail' => 'Failed to Submit Correction!']);
        }
    }
    // public function submitReviewChecklistOld(Request $request, $id)
    // {
    //     $id = decrypt($id);
    //     $nextStatus = (MstAssignChecklists::where('id_periode_checklist', $id)->whereNotIn('approve', [1, 3])->exists()) ? 2 : 4;
    //     $chekJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 5)->get();
    //     foreach ($chekJars as $item) {
    //         $item->isApprove = (MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->whereNotIn('approve', [1, 3])->exists()) ? 0 : 1;
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Update Period
    //         MstPeriodeChecklists::where('id', $id)->update(['status' => $nextStatus]);
    //         // Update Checklist Jaringan
    //         foreach ($chekJars as $item) {
    //             if ($nextStatus == 4) {
    //                 ChecklistJaringan::where('id', $item->id)->update(['status' => 3, 'last_decision_assessor' => 2, 'last_decision_pic' => 0]);
    //             } else {
    //                 if ($item->isApprove == 1) {
    //                     ChecklistJaringan::where('id', $item->id)->update(['status' => 1, 'last_decision_assessor' => 2]);
    //                 } else {
    //                     ChecklistJaringan::where('id', $item->id)->update(['status' => 4, 'last_decision_assessor' => 1]);
    //                 }
    //             }
    //         }
    //         // Update Assign Checklist
    //         MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 1)->update(['approve' => 3]);
    //         MstAssignChecklists::where('id_periode_checklist', $id)->where('approve', 0)->update(['approve' => 2]);

    //         // MAILING
    //         // [ INITIATE VARIABLE ] 
    //         $variableEmail = $this->variableEmail();
    //         $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type', DB::raw('(SELECT COUNT(*) FROM mst_assign_checklists WHERE mst_assign_checklists.id_periode_checklist = mst_periode_checklists.id) as totalChecklist'))
    //             ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
    //             ->where('mst_periode_checklists.id', $id)
    //             ->first();
    //         // Group By Type Checklist For Create Paper Assign (Checklist Jaringan)
    //         $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
    //         $checklistdetail = ChecklistJaringan::where('id_periode', $id)
    //             ->orderByRaw("FIELD(type_checklist, '" . $sortOrder->implode("','") . "')")
    //             ->get();

    //         $emailAuditor = MstEmployees::leftjoin('users', 'users.email', 'mst_employees.email')->where('mst_employees.id_dealer', $periodInfo->id_branch)->where('users.role', 'Internal Auditor Dealer')->pluck('users.email');
    //         if ($emailAuditor->isEmpty()) {
    //             return redirect()->back()->with(['fail' => 'Failed, Data Employee Internal Auditor Jaringan "' . $periodInfo->dealer_name . '" Not Exist']);
    //         }
    //         // Recepient Email
    //         if ($variableEmail['devRule'] == 1) {
    //             $toemail = $variableEmail['emailDev'];
    //             $ccemail = null;
    //         } else {
    //             // IF Reject Send Back To Internal Auditor
    //             if ($nextStatus == 2) {
    //                 $toemail = $emailAuditor;
    //             }
    //             // IF Approve Send To PIC NOS MD
    //             else {
    //                 $toemail = User::where('role', 'PIC NOS MD')->pluck('email')->toArray();
    //             }
    //             $ccemail = $variableEmail['emailSubmitter'];
    //         }
    //         // Mail Structure
    //         $mailStructure = new SubmitReviewChecklist($nextStatus, $periodInfo, $checklistdetail, $variableEmail['emailSubmitter'], $request->note);
    //         // Send Email
    //         Mail::to($toemail)->cc($ccemail)->send($mailStructure);

    //         //Log Period
    //         $this->storeLogPeriod($id, $nextStatus, $request->note);
    //         //Audit Log
    //         $this->auditLogsShort('Assessor Submit Review Checklist Jaringan :' . $id);
    //         DB::commit();
    //         return redirect()->back()->with(['success' => 'Success Submit Review']);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['fail' => 'Failed to Submit Review!']);
    //     }
    // }

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
            return redirect()->route('listassigned.periodDetail', encrypt($request->idPeriod))->with(['success' => 'Success Update Decision']);
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
        $toemail = $ccemail = null;
        if ($variableEmail['devRule'] == 1) {
            $toemail = $variableEmail['emailDev'];
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

            $chekJars = ChecklistJaringan::where('id_periode', $id)->where('status', '!=', 5)->get();
            // Update Checklist Jaringan
            foreach ($chekJars as $item) {
                // If Reject
                if ($item->last_decision_pic == 1) {
                    // Update Assign Checklist
                    MstAssignChecklists::where('id_periode_checklist', $id)->where('type_checklist', $item->type_checklist)->update(['approve' => 1, 'note_assesor' => null]);

                    // Rollback Response Assesor To Null
                    ChecklistJaringan::where('id', $item->id)->update([
                        'status' => 2, 'last_decision_assessor' => 0, 'last_correction_assessor' => null,
                        'total_point_assesor' => null, 'result_percentage_assesor' => null,
                        'audit_result_assesor' => null, 'mandatory_item_assesor' => null, 'result_final_assesor' => null
                    ]);
                    ChecklistResponses::where('id_checklist_jaringan', $item->id)->update(['response_correction' => null]);
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

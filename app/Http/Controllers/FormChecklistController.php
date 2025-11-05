<?php

namespace App\Http\Controllers;

use App\Models\ChecklistResponse;
use App\Models\MstAssignChecklists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

// Trait
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstGrading;
use App\Models\MstPeriodeChecklists;
use App\Models\MstRules;
use App\Models\User;
use App\Models\ChecklistJaringan;
use Carbon\Carbon;

// Mail
use App\Mail\SubmitChecklist;
use App\Models\ChecklistResponses;

class FormChecklistController extends Controller
{
    use AuditLogsTrait;

    public function checklist($id, Request $request)
    {
        $id = decrypt($id);
        $period = ChecklistJaringan::where('id', $id)->first();
        $typeChecklist = $period->type_checklist;
        $idPeriod = $period->id_periode;

        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $view = in_array($typeChecklist, $typeChecklistPerCheck) ? 'formaudit.perCheck' : 'formaudit.perParent';

        //Auditlog
        $this->auditLogsShort('View Checklist Form:', $id);

        return view($view, compact('id', 'idPeriod'));
    }

    // CALCULATE TOTAL POINT
    public function calculatePoint($checkJar)
    {
        $totalPoint = null;
        $responses = ChecklistResponses::where('id_checklist_jaringan', $checkJar->id)->where('status_response', 1)->get();
        $totalResp = $responses->count();
        $remaining = $checkJar->total_checklist - $totalResp;
        $totalEG = $responses->where('response', 'Exist, Good')->count();
        $totalENG = $responses->where('response', 'Exist Not Good')->count();

        // H1 / H23 Premises = EG*1 + ENG*-1 + NE/NA*0
        // H1 / H23 People = EG*2 + ENG*1 + NE/NA*0
        // H1 / H23 Process = EG*1 + ENG*0 + NE/NA*0
        if (in_array($checkJar->type_checklist, ['H1 Premises', 'H23 Premises'])) {
            $totalPoint = $totalEG - $totalENG;
            $result = ($totalResp > 0) ? ($totalPoint / $totalResp) * 100 : 0;
        } elseif (in_array($checkJar->type_checklist, ['H1 People', 'H23 People'])) {
            $totalPoint = ($totalEG * 2) + $totalENG;
            $result = ($totalResp > 0) ? ($totalPoint / (2 * $totalResp)) * 100 : 0;
        } elseif (in_array($checkJar->type_checklist, ['H1 Process', 'H23 Process'])) {
            $totalPoint = $totalEG;
            $result = ($totalResp > 0) ? ($totalPoint / $totalResp) * 100 : 0;
        } else {
            return [
                'success' => false,
                'remaining' => null,
                'totalPoint' => null,
                'percentageResult' => null
            ];
        }
        return [
            'success' => true,
            'remaining' => $remaining,
            'totalPoint' => $totalPoint,
            'percentageResult' => number_format($result, 2, '.', '')
        ];
    }

    // List Per Checklist
    public function getFormPerCheck(Request $request, $id)
    {
        // id checklist_jaringan
        $id = decrypt($id);
        $checkJar = ChecklistJaringan::find($id);
        $type = $checkJar->type_checklist;
        $idPeriod = $checkJar->id_periode;

        // Save Response 
        if ($request->responseAns != null) {
            $statusResp = ($request->responseAns == 'N/A' || $request->responseAns == 'Not Exist') ? 1
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            
            DB::beginTransaction();
            try {
                ChecklistResponses::updateOrCreate(
                    ['id_assign_checklist' => $request->idActive],
                    ['id_checklist_jaringan' => $id, 'response' => $request->responseAns, 'status_response' => $statusResp]
                );
                // Calculate points
                $calculate = $this->calculatePoint($checkJar);
                if ($calculate['success']) {
                    $checkJar->update(['checklist_remaining' => $calculate['remaining'], 'total_point' => $calculate['totalPoint'], 'result_percentage' => $calculate['percentageResult']]);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
        }

        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_parent AS UNSIGNED)')
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();

        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)->orderBy('mst_assign_checklists.order_no_parent')->get()->groupBy('parent_point_checklist')
            ->map(function ($group) {
                $sortedGroup = $group->sortBy('order_no_checklist');
                $responses = $sortedGroup->pluck('status_response')->toArray();
                $isFullFilled = in_array(null, $responses) ? 0 : 1;

                $approval = $sortedGroup->pluck('approve')->toArray();
                $rejected = in_array(2, $approval) ? 1 : 0;
                return ['parent_point_checklist' => $sortedGroup->first()->parent_point_checklist, 'firstIdQuestion' => $sortedGroup->first()->id, 'isFullFilled' => $isFullFilled, 'order' => $sortedGroup->first()->order_no_parent, 'rejected' => $rejected];
            })
            ->sortBy('order')
            ->values();

        $points = MstAssignChecklists::select('mst_assign_checklists.id', 'checklist_responses.status_response', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)
            ->where('parent_point_checklist', $tabParentAct)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();

        $question = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.path_input_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id', $idQuestionAct)
            ->first();
        $options = collect(json_decode($question->mark, true));

        // Convert the collection to an array
        $assignsArray = $assigns->toArray();
        $tabParentPrev = $idQuestionPrev = $tabParentNext = $idQuestionNext = $currentIndex = null;
        foreach ($assignsArray as $index => $assign) {
            if ($assign['id'] == $idQuestionAct && $assign['parent_point_checklist'] == $tabParentAct) {
                $currentIndex = $index;
                break;
            }
        }
        // Get previous and next values if available
        if ($currentIndex !== null) {
            if (isset($assignsArray[$currentIndex - 1])) {
                $tabParentPrev = $assignsArray[$currentIndex - 1]['parent_point_checklist'];
                $idQuestionPrev = $assignsArray[$currentIndex - 1]['id'];
            }
            if (isset($assignsArray[$currentIndex + 1])) {
                $tabParentNext = $assignsArray[$currentIndex + 1]['parent_point_checklist'];
                $idQuestionNext = $assignsArray[$currentIndex + 1]['id'];
            }
        }

        $response = [
            'id' => $id, 'idPeriod' => $idPeriod, 'assigns' => $assigns, 'tabLists' => $tabLists,
            'points' => $points, 'question' => $question, 'options' => $options,
            'tabParentPrev' => $tabParentPrev, 'idQuestionPrev' => $idQuestionPrev,
            'tabParentAct' => $tabParentAct, 'idQuestionAct' => $idQuestionAct,
            'tabParentNext' => $tabParentNext, 'idQuestionNext' => $idQuestionNext,
        ];
        return response()->json($response);
    }
    public function storeFileFormPerCheck(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Save Response 
            if ($request->responseFile != null) {
                $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
                if ($exist) {
                    if (File::exists($exist->path_input_response)) {
                        File::delete($exist->path_input_response);
                    }
                }
                $file = $request->file('responseFile');
                $name = $file->hashName();
                $file->move(public_path('assets/images/response_checklist'), $name);
                $url = 'assets/images/response_checklist/' . $name;

                ChecklistResponses::updateOrCreate(
                    ['id_assign_checklist' => $request->idActive],
                    ['id_checklist_jaringan' => $request->idCheckJar, 'path_input_response' => $url]
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function getFormPerParent(Request $request, $id)
    {
        // id checklist_jaringan
        $id = decrypt($id);
        $checkJar = ChecklistJaringan::find($id);
        $type = $checkJar->type_checklist;
        $idPeriod = $checkJar->id_periode;

        // Save Response 
        if ($request->responseAns != null) {
            $statusResp = 1;

            DB::beginTransaction();
            try {
                ChecklistResponses::updateOrCreate(
                    ['id_assign_checklist' => $request->idActive],
                    ['id_checklist_jaringan' => $id, 'response' => $request->responseAns, 'status_response' => $statusResp]
                );
                // Calculate points
                $calculate = $this->calculatePoint($checkJar);
                if ($calculate['success']) {
                    $checkJar->update(['checklist_remaining' => $calculate['remaining'], 'total_point' => $calculate['totalPoint'], 'result_percentage' => $calculate['percentageResult']]);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
        }

        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_parent AS UNSIGNED)')
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();
        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response', 'checklist_responses.path_input_response', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)->orderBy('mst_assign_checklists.order_no_parent')->get()->groupBy('parent_point_checklist')
            ->map(function ($group) {
                $sortedGroup = $group->sortBy('order_no_checklist');
                $responses = $sortedGroup->pluck('status_response')->toArray();
                $isFullFilled = in_array(null, $responses) ? 0 : 1;
                if ($isFullFilled == 1) {
                    $responses = $sortedGroup->pluck('path_input_response')->toArray();
                    $isFullFilled = in_array(null, $responses) ? 0 : 1;
                }

                $approval = $sortedGroup->pluck('approve')->toArray();
                $rejected = in_array(2, $approval) ? 1 : 0;
                return ['parent_point_checklist' => $sortedGroup->first()->parent_point_checklist, 'firstIdQuestion' => $sortedGroup->first()->id, 'isFullFilled' => $isFullFilled, 'order' => $sortedGroup->first()->order_no_parent, 'rejected' => $rejected];
            })
            ->sortBy('order')
            ->values();

        $points = MstAssignChecklists::select('mst_assign_checklists.id', 'checklist_responses.status_response', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type)
            ->where('parent_point_checklist', $tabParentAct)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();
        $question = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.path_input_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id', $idQuestionAct)
            ->first();
        $options = collect(json_decode($question->mark, true));

        // Convert the collection to an array
        $assignsArray = $assigns->toArray();
        $tabParentPrev = $idQuestionPrev = $tabParentNext = $idQuestionNext = $currentIndex = null;
        foreach ($assignsArray as $index => $assign) {
            if ($assign['id'] == $idQuestionAct && $assign['parent_point_checklist'] == $tabParentAct) {
                $currentIndex = $index;
                break;
            }
        }
        // Get previous and next values if available
        if ($currentIndex !== null) {
            if (isset($assignsArray[$currentIndex - 1])) {
                $tabParentPrev = $assignsArray[$currentIndex - 1]['parent_point_checklist'];
                $idQuestionPrev = $assignsArray[$currentIndex - 1]['id'];
            }
            if (isset($assignsArray[$currentIndex + 1])) {
                $tabParentNext = $assignsArray[$currentIndex + 1]['parent_point_checklist'];
                $idQuestionNext = $assignsArray[$currentIndex + 1]['id'];
            }
        }

        $response = [
            'id' => $id, 'idPeriod' => $idPeriod, 'assigns' => $assigns, 'tabLists' => $tabLists,
            'points' => $points, 'question' => $question, 'options' => $options,
            'tabParentPrev' => $tabParentPrev, 'idQuestionPrev' => $idQuestionPrev,
            'tabParentAct' => $tabParentAct, 'idQuestionAct' => $idQuestionAct,
            'tabParentNext' => $tabParentNext, 'idQuestionNext' => $idQuestionNext,
        ];
        return response()->json($response);
    }
    public function storeFileFormPerParent(Request $request)
    {
        // Validation Format File Upload Max 2mb
        $request->validate([
            'responseFile' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,ogg,mp3,wav,pdf,doc,docx,xls,xlsx,zip,rar|max:20480'
        ]);

        DB::beginTransaction();
        try {
            // Save Response 
            if ($request->responseFile != null) {
                $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
                if ($exist) {
                    if (File::exists($exist->path_input_response)) {
                        File::delete($exist->path_input_response);
                    }
                }
                $file = $request->file('responseFile');
                $name = $file->hashName();
                $file->move(public_path('assets/images/response_checklist'), $name);
                $url = 'assets/images/response_checklist/' . $name;

                $parent = MstAssignChecklists::where('id', $request->idActive)->first()->parent_point_checklist;
                $checkJar = ChecklistJaringan::where('id', $request->idCheckJar)->first();
                $type = $checkJar->type_checklist;
                $idPeriod = $checkJar->id_periode;
                $assigns = MstAssignChecklists::where('id_periode_checklist', $idPeriod)->where('type_checklist', $type)->where('parent_point_checklist', $parent)->get();
                foreach ($assigns as $item) {
                    ChecklistResponses::updateOrCreate(
                        ['id_assign_checklist' => $item->id],
                        ['path_input_response' => $url]
                    );
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function finishChecklist(Request $request)
    {
        $id = $request->idCheckJar;
        $checkJar = ChecklistJaringan::find($id);
        $type = $checkJar->type_checklist;
        $idPeriod = $checkJar->id_periode;

        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $model = in_array($type, $typeChecklistPerCheck) ? 'perCheck' : 'perParent';

        if($model == 'perCheck'){
            $request->validate([
                'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);
        } else {
            $request->validate([
                'responseFile' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,ogg,mp3,wav,pdf,doc,docx,xls,xlsx,zip,rar|max:20480'
            ]);
        }

        DB::beginTransaction();
        try {
            // Save Response File
            if ($request->hasFile('responseFile')) {
                $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
                if ($exist) {
                    if (File::exists($exist->path_input_response)) {
                        File::delete($exist->path_input_response);
                    }
                }
                $file = $request->file('responseFile');
                $name = $file->hashName();
                $file->move(public_path('assets/images/response_checklist'), $name);
                $url = 'assets/images/response_checklist/' . $name;

                if($model == 'perCheck'){
                    ChecklistResponses::updateOrCreate(
                        ['id_assign_checklist' => $request->idActive],
                        ['id_checklist_jaringan' => $request->idCheckJar, 'path_input_response' => $url]
                    );
                } else {
                    $parent = MstAssignChecklists::where('id', $request->idActive)->first()->parent_point_checklist;
                    $checkJar = ChecklistJaringan::where('id', $request->idCheckJar)->first();
                    $type = $checkJar->type_checklist;
                    $idPeriod = $checkJar->id_periode;
                    $assigns = MstAssignChecklists::where('id_periode_checklist', $idPeriod)->where('type_checklist', $type)->where('parent_point_checklist', $parent)->get();
                    foreach ($assigns as $item) {
                        ChecklistResponses::updateOrCreate(
                            ['id_assign_checklist' => $item->id],
                            ['path_input_response' => $url]
                        );
                    }
                }
            }
            // Save Response Answer
            if ($request->has('responseAns') && !is_null($request->responseAns) && $request->responseAns !== 'undefined') {
                if($model == 'perCheck'){
                    $statusResp = ($request->responseAns == 'N/A' || $request->responseAns == 'Not Exist') ? 1
                        : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
                } else {
                    $statusResp = 1;
                }

                ChecklistResponses::updateOrCreate(
                    ['id_assign_checklist' => $request->idActive],
                    ['id_checklist_jaringan' => $id, 'response' => $request->responseAns, 'status_response' => $statusResp]
                );
                // Calculate points
                $calculate = $this->calculatePoint($checkJar);
                if ($calculate['success']) {
                    $checkJar->update(['checklist_remaining' => $calculate['remaining'], 'total_point' => $calculate['totalPoint'], 'result_percentage' => $calculate['percentageResult']]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

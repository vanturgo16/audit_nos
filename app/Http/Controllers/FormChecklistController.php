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

    public function checklistForm($id, Request $request)
    {
        $id = decrypt($id);
        $period = ChecklistJaringan::where('id', $id)->first();
        $typeChecklist = $period->type_checklist;
        $idPeriod = $period->id_periode;

        //Auditlog
        $this->auditLogsShort('View Checklist Form:', $id);

        $view = $typeChecklist == 'H1 Premises' ? 'auditor.form.h1p' : 'auditor.form.other';
        return view($view, compact('id', 'idPeriod'));
    }

    // H1 Premises
    public function getChecklistFormH1P(Request $request, $id)
    {
        // id_checklist Jaringan
        $id = decrypt($id);

        // Save Response 
        if ($request->responseAns != null) {
            $statusResp = ($request->responseAns == 'N/A' || $request->responseAns == 'Not Exist') ? 1
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $id, 'response' => $request->responseAns, 'status_response' => $statusResp]
            );

            // Calculate Point
            $checklistJaringan = ChecklistJaringan::find($id);
            $checked = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->count();
            $remaining = $checklistJaringan->total_checklist - $checked;
            // Calculate totals
            $responses = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->get();
            $totalEG = $responses->where('response', 'Exist, Good')->count();
            $totalENG = $responses->where('response', 'Exist Not Good')->count();
            $totalPoint = $totalEG - $totalENG;
            // Avoid division by zero
            $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
            $formattedResult = number_format($result, 2, '.', '');
            // Update Checklist Jaringan
            $checklistJaringan->update(['checklist_remaining' => $remaining, 'total_point' => $totalPoint, 'result_percentage' => $formattedResult]);
        }

        $type = ChecklistJaringan::where('id', $id)->first();
        $idPeriod = $type->id_periode;
        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_parent AS UNSIGNED)')
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();

        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)->orderBy('mst_assign_checklists.order_no_parent')->get()->groupBy('parent_point_checklist')
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
            ->where('type_checklist', $type->type_checklist)
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
    public function storeChecklistFileH1P(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

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

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $request->idCheckJar, 'path_input_response' => $url, 'status_response' => $statusResp]
            );

            // Calculate Point
            $id = $request->idCheckJar;
            $checklistJaringan = ChecklistJaringan::find($id);
            $checked = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->count();
            $remaining = $checklistJaringan->total_checklist - $checked;
            // Calculate totals
            $responses = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->get();
            $totalEG = $responses->where('response', 'Exist, Good')->count();
            $totalENG = $responses->where('response', 'Exist Not Good')->count();
            $totalPoint = $totalEG - $totalENG;
            // Avoid division by zero
            $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
            $formattedResult = number_format($result, 2, '.', '');
            // Update Checklist Jaringan
            $checklistJaringan->update(['checklist_remaining' => $remaining, 'total_point' => $totalPoint, 'result_percentage' => $formattedResult]);
        }
    }
    public function finishChecklistH1P(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Save Response 
        if ($request->has('responseAns') && !is_null($request->responseAns) && $request->responseAns !== 'undefined') {
            $statusResp = ($request->responseAns == 'N/A' || $request->responseAns == 'Not Exist') ? 1
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $request->idCheckJar, 'response' => $request->responseAns, 'status_response' => $statusResp]
            );
        }
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

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $request->idCheckJar, 'path_input_response' => $url, 'status_response' => $statusResp]
            );
        }

        // Calculate Point
        $id = $request->idCheckJar;
        $checklistJaringan = ChecklistJaringan::find($id);
        $checked = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->count();
        $remaining = $checklistJaringan->total_checklist - $checked;
        // Calculate totals
        $responses = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->get();
        $totalEG = $responses->where('response', 'Exist, Good')->count();
        $totalENG = $responses->where('response', 'Exist Not Good')->count();
        $totalPoint = $totalEG - $totalENG;
        // Avoid division by zero
        $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
        $formattedResult = number_format($result, 2, '.', '');
        // Update Checklist Jaringan
        $checklistJaringan->update(['checklist_remaining' => $remaining, 'total_point' => $totalPoint, 'result_percentage' => $formattedResult]);
    }

    // H1 People = EG*2 + ENG*1 + NE/NA*0
    // H1 Process = EG*1 + ENG*0 + NE/NA*0
    // H23 Premises = EG*1 + ENG*-1 + NE/NA*0
    // H23 People = EG*2 + ENG*1 + NE/NA*0
    // H23 Process = EG*1 + ENG*0 + NE/NA*0
    public function getChecklistForm(Request $request, $id)
    {
        // id_checklist Jaringan
        $id = decrypt($id);

        // Save Response 
        if ($request->responseAns != null) {
            $statusResp = 1;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $id, 'response' => $request->responseAns, 'status_response' => $statusResp]
            );

            // Calculate Point
            $checklistJaringan = ChecklistJaringan::find($id);
            $checked = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->count();
            $remaining = $checklistJaringan->total_checklist - $checked;
            // Calculate totals
            $responses = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->get();
            $totalEG = $responses->where('response', 'Exist, Good')->count();
            $totalENG = $responses->where('response', 'Exist Not Good')->count();
            if (in_array($checklistJaringan->type_checklist, ['H1 People', 'H23 People'])) {
                $totalPoint = ($totalEG * 2) + $totalENG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / (2 * $checked)) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            } elseif (in_array($checklistJaringan->type_checklist, ['H1 Process', 'H23 Process'])) {
                $totalPoint = $totalEG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            } else {
                $totalPoint = $totalEG - $totalENG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            }
            // Update Checklist Jaringan
            $checklistJaringan->update(['checklist_remaining' => $remaining, 'total_point' => $totalPoint, 'result_percentage' => $formattedResult]);
        }

        $type = ChecklistJaringan::where('id', $id)->first();
        $idPeriod = $type->id_periode;

        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->orderByRaw('CAST(mst_assign_checklists.order_no_parent AS UNSIGNED)')
            ->orderByRaw('CAST(mst_assign_checklists.order_no_checklist AS UNSIGNED)')
            ->get();
        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response', 'checklist_responses.path_input_response', 'mst_assign_checklists.order_no_parent', 'mst_assign_checklists.order_no_checklist', 'mst_assign_checklists.approve')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)->orderBy('mst_assign_checklists.order_no_parent')->get()->groupBy('parent_point_checklist')
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
            ->where('type_checklist', $type->type_checklist)
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
    public function storeChecklistFile(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        // $request->validate([
        //     'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        // ]);
        $request->validate([
            'responseFile' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,ogg,mp3,wav,pdf,doc,docx,xls,xlsx,zip,rar|max:20480'
        ]);

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
    }
    public function finishChecklist(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        // $request->validate([
        //     'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        // ]);
        $request->validate([
            'responseFile' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,ogg,mp3,wav,pdf,doc,docx,xls,xlsx,zip,rar|max:20480'
        ]);

        // Save Response 
        if ($request->has('responseAns') && !is_null($request->responseAns) && $request->responseAns !== 'undefined') {
            $statusResp = 1;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['id_checklist_jaringan' => $request->idCheckJar, 'response' => $request->responseAns, 'status_response' => $statusResp]
            );

            // Calculate Point
            $id = $request->idCheckJar;
            $checklistJaringan = ChecklistJaringan::find($id);
            $checked = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->count();
            $remaining = $checklistJaringan->total_checklist - $checked;
            // Calculate totals
            $responses = ChecklistResponses::where('id_checklist_jaringan', $id)->where('status_response', 1)->get();
            $totalEG = $responses->where('response', 'Exist, Good')->count();
            $totalENG = $responses->where('response', 'Exist Not Good')->count();
            if (in_array($checklistJaringan->type_checklist, ['H1 People', 'H23 People'])) {
                $totalPoint = ($totalEG * 2) + $totalENG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / (2 * $checked)) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            } elseif (in_array($checklistJaringan->type_checklist, ['H1 Process', 'H23 Process'])) {
                $totalPoint = $totalEG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            } else {
                $totalPoint = $totalEG - $totalENG;
                // Avoid division by zero
                $result = ($checked > 0) ? ($totalPoint / $checked) * 100 : 0;
                $formattedResult = number_format($result, 2, '.', '');
            }
            // Update Checklist Jaringan
            $checklistJaringan->update(['checklist_remaining' => $remaining, 'total_point' => $totalPoint, 'result_percentage' => $formattedResult]);
        }
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
}

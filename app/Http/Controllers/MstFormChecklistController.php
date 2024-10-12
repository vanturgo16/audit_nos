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

class MstFormChecklistController extends Controller
{
    use AuditLogsTrait;

    public function jaringanList(Request $request)
    {
        if ($request->ajax()) {
            $datas = MstJaringan::get();
            $data = DataTables::of($datas)->addColumn('action', function ($data) {
                return view('formchecklist.action.index', compact('data'));
            })->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View Jaringan Checklist');
        
        return view('formchecklist.jaringan_list');
    }

    public function auditor(Request $request)
    {
        $email_user = auth()->user()->email;
        $id = MstEmployees::where('email', $email_user)->first()->id_dealer;
        
        $datas = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
        ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
        ->orderBy('mst_periode_checklists.created_at')
        ->where('mst_dealers.id', $id)
        ->where('mst_periode_checklists.is_active', '1')
        ->get();

        $jaringan = MstJaringan::where('id', $id)->first()->dealer_name;


        //Audit Log
        $this->auditLogsShort('View Periode Form Checklist');

        return view('formchecklist.periode',compact('datas', 'id', 'jaringan'));
    }

    public function periodList(Request $request, $id)
    {
        $id = decrypt($id);

        if ($request->ajax()) {
            $datas = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->orderBy('mst_periode_checklists.created_at')
                ->where('mst_dealers.id', $id)
                ->where('mst_periode_checklists.is_active', '1')
                ->get();
            $data = DataTables::of($datas)->addColumn('action', function ($data) {
                return view('formchecklist.action.period', compact('data'));
            })->toJson();
            return $data;
        }
        $jaringan = MstJaringan::where('id', $id)->first()->dealer_name;

        //Audit Log
        $this->auditLogsShort('View Periode Form Checklist');

        return view('formchecklist.period_list',compact('id', 'jaringan'));
    }

    public function typeChecklistList(Request $request, $id)
    {
        $id = decrypt($id); // ID Period

        $grading = MstGrading::all();
        $period = MstPeriodeChecklists::where('id', $id)->first();
        
        $sortdropdown = MstDropdowns::where('category', 'Type Checklist')->orderby('created_at')->pluck('name_value')->toArray();
        $datas = ChecklistJaringan::where('id_periode', $id)->orderByRaw("FIELD(type_checklist, '" . implode("','", $sortdropdown) . "')")->get();
        foreach($datas as $item){
            $responsCounts = ChecklistResponse::select('checklist_response.response as response')
                ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                ->where('mst_periode_checklists.id', $id)
                ->groupBy('checklist_response.response')
                ->selectRaw('checklist_response.response as type_response, COUNT(*) as count')
                ->get()->toArray();
            $mandatoryCounts = ChecklistResponse::selectRaw('
                    SUM(mst_assign_checklists.ms = 1 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as sgp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 1 AND mst_assign_checklists.mp = 1) as gp,
                    SUM(mst_assign_checklists.ms = 0 AND mst_assign_checklists.mg = 0 AND mst_assign_checklists.mp = 1) as p
                ')
                ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_assign_checklists.type_checklist', $item->type_checklist)
                ->whereNot('checklist_response.response', 'Exist, Good')
                ->where('mst_periode_checklists.id', $id)
                ->get()->toArray();
            $item->point = $responsCounts;
            $item->mandatory = $mandatoryCounts;
            $item->decisionpic = $period->decisionpic;
        }

        // Get Status
        $hasStatusFive = $datas->contains('status', 5);
        $hasStatusOne = $datas->contains('status', 1);
        $hasOtherStatuses = $datas->contains(function($item) {
            return !in_array($item['status'], [1, 5]) && $item['status'] >= 0 && $item['status'] <= 9;
        }); $status = $hasStatusFive || ($hasStatusOne && !$hasOtherStatuses);

        if ($request->ajax()) {
            $today = Carbon::today()->format('Y-m-d');
            $data = DataTables::of($datas)->addColumn('action', function ($data) use ($grading, $period, $today) {
                return view('formchecklist.action.typechecklist', compact('data', 'grading', 'period', 'today'));
            })->toJson();
            return $data;
        }

        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('formchecklist.typechecklist_list',compact('id', 'period', 'status'));
    }

    public function startChecklist($id)
    {
        $id = decrypt($id);
        
        DB::beginTransaction();
        try{
            ChecklistJaringan::where('id', $id)->update([
                'status' => '0',
                'start_date' => Carbon::now(),
            ]);

            //Audit Log
            $this->auditLogsShort('Start Checklist :', $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Start Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Start Checklist!']);
        }
    }

    public function checklistForm($id, Request $request)
    {
        $id = decrypt($id);
        $period = ChecklistJaringan::where('id', $id)->first();
        $typeChecklist = $period->type_checklist;
        $idPeriod = $period->id_periode;
        
        //Auditlog
        $this->auditLogsShort('View Checklist Form:', $id);

        // $view = $typeChecklist == 'H1 Premises' ? 'formchecklist.form_check_h1p' : 'formchecklist.form_check';
        // return view($view, compact('id', 'idPeriod'));


        return view('formchecklist.form_check', compact('id', 'idPeriod'));
    }

    public function getChecklistForm(Request $request, $id)
    {
        // id_checklist Jaringan
        $id = decrypt($id); 

        // Save Response 
        if($request->responseAns != null){
            $statusResp = ($request->responseAns == 'N/A') ? 1 
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['response' => $request->responseAns, 'status_response' => $statusResp]
            );
        }
        
        $type = ChecklistJaringan::where('id', $id)->first();
        $idPeriod = $type->id_periode;

        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->orderBy('mst_assign_checklists.id')
            ->get();
        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)->orderBy('mst_assign_checklists.id')->get()->groupBy('parent_point_checklist')
            ->map(function ($group) {
                $responses = $group->pluck('status_response')->toArray(); $isFullFilled = in_array(null, $responses) ? 0 : 1;
                return [ 'parent_point_checklist' => $group->first()->parent_point_checklist, 'firstIdQuestion' => $group->first()->id, 'isFullFilled' => $isFullFilled ];
            })
            ->sortBy('firstIdQuestion')
            ->values();
        $points = MstAssignChecklists::select('mst_assign_checklists.id', 'checklist_responses.status_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->where('parent_point_checklist', $tabParentAct)
            ->orderBy('mst_assign_checklists.id')
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
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Save Response 
        if($request->responseFile != null){
            $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
            if($exist) {
                if (File::exists($exist->path_input_response)) {
                    File::delete($exist->path_input_response);
                }
            }
            $file = $request->file('responseFile');
            $name = $file ->hashName();
            $file->move(public_path('assets/images/response_checklist'), $name);
            $url = 'assets/images/response_checklist/'.$name;

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['path_input_response' => $url, 'status_response' => $statusResp]
            );
        }
    }
    public function finishChecklist(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Save Response 
        if($request->has('responseAns') && !is_null($request->responseAns) && $request->responseAns !== 'undefined'){
            $statusResp = ($request->responseAns == 'N/A') ? 1 
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['response' => $request->responseAns, 'status_response' => $statusResp]
            );
        }
        if($request->hasFile('responseFile')){
            $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
            if($exist) {
                if (File::exists($exist->path_input_response)) {
                    File::delete($exist->path_input_response);
                }
            }
            $file = $request->file('responseFile');
            $name = $file ->hashName();
            $file->move(public_path('assets/images/response_checklist'), $name);
            $url = 'assets/images/response_checklist/'.$name;

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['path_input_response' => $url, 'status_response' => $statusResp]
            );
        }
    }

    public function getChecklistFormH1P(Request $request, $id)
    {
        // id_checklist Jaringan
        $id = decrypt($id); 

        // Save Response 
        if($request->responseAns != null){
            $statusResp = ($request->responseAns == 'N/A') ? 1 
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['response' => $request->responseAns, 'status_response' => $statusResp]
            );
        }
        
        $type = ChecklistJaringan::where('id', $id)->first();
        $idPeriod = $type->id_periode;

        $assigns = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.parent_point_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->orderBy('mst_assign_checklists.id')
            ->get();
        $tabParentAct = $request->tabParent == null ? $assigns->first()->parent_point_checklist : $request->tabParent;
        $idQuestionAct = $request->idQuestion == null ? intval($assigns->first()->id) : intval($request->idQuestion);

        $tabLists = MstAssignChecklists::select('mst_assign_checklists.parent_point_checklist', 'mst_assign_checklists.id', 'checklist_responses.status_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)->orderBy('mst_assign_checklists.id')->get()->groupBy('parent_point_checklist')
            ->map(function ($group) {
                $responses = $group->pluck('status_response')->toArray(); $isFullFilled = in_array(null, $responses) ? 0 : 1;
                return [ 'parent_point_checklist' => $group->first()->parent_point_checklist, 'firstIdQuestion' => $group->first()->id, 'isFullFilled' => $isFullFilled ];
            })
            ->sortBy('firstIdQuestion')
            ->values();
        $points = MstAssignChecklists::select('mst_assign_checklists.id', 'checklist_responses.status_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', '=', 'checklist_responses.id_assign_checklist')
            ->where('id_periode_checklist', $idPeriod)
            ->where('type_checklist', $type->type_checklist)
            ->where('parent_point_checklist', $tabParentAct)
            ->orderBy('mst_assign_checklists.id')
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
        if($request->responseFile != null){
            $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
            if($exist) {
                if (File::exists($exist->path_input_response)) {
                    File::delete($exist->path_input_response);
                }
            }
            $file = $request->file('responseFile');
            $name = $file ->hashName();
            $file->move(public_path('assets/images/response_checklist'), $name);
            $url = 'assets/images/response_checklist/'.$name;

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['path_input_response' => $url, 'status_response' => $statusResp]
            );
        }
    }
    public function finishChecklistH1P(Request $request)
    {
        // Validation Format File Upload Image Max 2mb
        $request->validate([
            'responseFile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Save Response 
        if($request->has('responseAns') && !is_null($request->responseAns) && $request->responseAns !== 'undefined'){
            $statusResp = ($request->responseAns == 'N/A') ? 1 
                : (ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('path_input_response')->exists() ? 1 : null);
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['response' => $request->responseAns, 'status_response' => $statusResp]
            );
        }
        if($request->hasFile('responseFile')){
            $exist = ChecklistResponses::where('id_assign_checklist', $request->idActive)->first();
            if($exist) {
                if (File::exists($exist->path_input_response)) {
                    File::delete($exist->path_input_response);
                }
            }
            $file = $request->file('responseFile');
            $name = $file ->hashName();
            $file->move(public_path('assets/images/response_checklist'), $name);
            $url = 'assets/images/response_checklist/'.$name;

            $statusResp = ChecklistResponses::where('id_assign_checklist', $request->idActive)->whereNotNull('response')->exists() ? 1 : null;
            ChecklistResponses::updateOrCreate(
                ['id_assign_checklist' => $request->idActive],
                ['path_input_response' => $url, 'status_response' => $statusResp]
            );
        }
    }

    public function store($id, Request $request)
    {
        $id = decrypt($id);//id_periode
        // dd($request->all());

        DB::beginTransaction();
        try{
            // Store or Update Parent Point Upload File
            if($request->hasFile('file_parent')){
                $id_parent = MstParentChecklists::where('parent_point_checklist', $request->parent_point)->first()->id;
                $file = FileInputResponse::join('trans_file_response', 'file_input_response.id_trans_file', 'trans_file_response.id')
                    ->where('parent_point', $request->parent_point)
                    ->where('id_period', $id)
                    ->first();
                if($file){
                    if (File::exists($file->path_url)) {
                        File::delete($file->path_url);
                    }
                    unlink($file);
                    $path_loc_thumb = $request->file('file_parent');
                    $name = $path_loc_thumb ->hashName();
                    $path_loc_thumb->move(public_path('assets/images/file_parent/'), $name);
                    $url_thumb = 'assets/images/file_parent/'.$name;

                    FileInputResponse::Where('id', $file->id)->update([
                        'path_url' => $url_thumb,
                    ]); 
                } else {
                    $path_loc_thumb = $request->file('file_parent');
                    $name = $path_loc_thumb ->hashName();
                    $path_loc_thumb->move(public_path('assets/images/file_parent/'), $name);
                    $url_thumb = 'assets/images/file_parent/'.$name;
                    $type_checklist = ChecklistJaringan::where('id', $request->id_checklist_jaringan)->first()->type_checklist;

                    $transFileResponse = TransFileResponse::create([
                        'id_period' => $id, 
                        'id_parent' => $id_parent,
                        'parent_point' => $request->parent_point,
                        'type_checklist' => $type_checklist
                    ]);

                    FileInputResponse::create([
                        'id_trans_file' => $transFileResponse->id,
                        'path_url' => $url_thumb,
                    ]);  
                }
            }

            foreach($request->except('_token', 'id_checklist_jaringan', 'parent_point', 'tabo', 'id_jaringan', 'sum_point', 'back') as $key=>$value){
                if (!preg_match('/^\d+question/', $key)) {
                    $pos = strpos($key, 'file_checklist'); // Cari posisi awal kata 'file_checklist'
                    if ($pos !== false) {
                        $id_assign = substr($key, $pos + strlen('file_checklist')); // Ambil substring setelah 'file_checklist'
                        if($value != null){
                            $respons = ChecklistResponse::select('checklist_response.id', 'checklist_response.path_input_response')
                            ->leftjoin('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                            ->Where('checklist_response.id_assign_checklist', $id_assign)
                            ->Where('mst_assign_checklists.id_periode_checklist', $id)
                            ->first();

                            // Store Image Response Checklist & Get Path URL if exist
                            $name = $value->hashName();
                            $value->move(public_path('assets/images/response_checklist'), $name);
                            $path_url_new = 'assets/images/response_checklist/' . $name;

                            if($respons == null){
                                ChecklistResponse::create([
                                    'id_assign_checklist' => $id_assign,
                                    'response' => 0,
                                    'path_input_response' => $path_url_new
                                ]);
                            } else {
                                //Delete File Before
                                $path_before = $respons->path_input_response;
                                if($path_before != null){
                                    $file_path = public_path($path_before);
                                    if (File::exists($file_path)) {
                                        File::delete($file_path);
                                    }
                                }
                                ChecklistResponse::where('id', $respons->id)->update([
                                    'path_input_response' =>  $path_url_new
                                ]);
                            }
                        }   
                    }
                }
                
            }

            $count = 0;
            foreach($request->except('_token', 'id_checklist_jaringan', 'parent_point', 'tabo', 'id_jaringan', 'sum_point', 'back') as $key=>$value){
                $id_assign = (int)substr($key,strlen('question'));

                if (!preg_match('/^\d+file_checklist/', $key)) {
                    $pos = strpos($key, 'question'); // Cari posisi awal kata 'question'
                    if ($pos !== false) {
                        $id_assign = substr($key, $pos + strlen('question')); // Ambil substring setelah 'question'
                        if($value != null){
                            $respons = ChecklistResponse::select('checklist_response.id')
                            ->leftjoin('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                            ->Where('checklist_response.id_assign_checklist', $id_assign)
                            ->Where('mst_assign_checklists.id_periode_checklist', $id)
                            ->first();

                            if($respons == null){
                                $count++;
                                ChecklistResponse::create([
                                    'id_assign_checklist' => $id_assign,
                                    'response' => $value
                                ]);

                                $get_total = ChecklistJaringan::where('id', $request->id_checklist_jaringan)->first();
                                $remaining = $get_total->checklist_remaining - 1;
                                if($remaining == 0){
                                    $status = 1;
                                }else{
                                    $status = $get_total->status;
                                }
                                ChecklistJaringan::where('id', $request->id_checklist_jaringan)->update([
                                    'checklist_remaining' => $remaining,
                                    'status' => $status,
                                ]);
                            } else {
                                ChecklistResponse::where('id', $respons->id)->update([
                                    'id_assign_checklist' => $id_assign,
                                    'response' => $value
                                ]);
                            }
                        }   
                    }
                }
                
            }
            //ngurangin data

            DB::commit();

            if($request->tabo == $request->sum_point && $request->back == null){
                return redirect()->route('formchecklist.typechecklist', encrypt($id))->with(['success' => 'Success Update Checklist']);
            }
            if($request->back){
                $request->session()->flash('tabo', ($request->tabo - 2));
                $additionalVariable = "1";
                $request->session()->flash('additionalVariable', $additionalVariable);
            } else {
                $request->session()->flash('tabo', $request->tabo);
            }
            return redirect()->route('formchecklist.checklistform', encrypt($request->id_jaringan));
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function submitchecklist($id, Request $request)
    {
        $id = decrypt($id);//id_periode
        // $request->validate([
        //     'percen_result' => 'required',
        //     'total_point' => 'required',
        //     'result_audit' => 'required'
        // ]);
        // dd($request);
        //belum bisaa
        
        $datas = ChecklistJaringan::all()->where('id_periode', $id);

        foreach($datas as $data){
            $responsCounts = ChecklistResponse::select('checklist_response.response as response')
            ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
            ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
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
            ->where('mst_assign_checklists.type_checklist', $datam->type_checklist)
            ->whereNot('checklist_response.response', 'Exist, Good')
            ->where('mst_periode_checklists.id', $id)
            ->get()->toArray();
            $datam->mandatory = $mandatoryCounts;
        }
        // dd($datas);
        $grading = MstGrading::all();

        DB::beginTransaction();
        try{
            //Update Last Submit Audit
            MstPeriodeChecklists::where('id', $id)->update([ 'last_submit_audit' => Carbon::now() ]);

            foreach($datas as $data_point):
                $totalPoint = 0;

                foreach($data_point->point as $point):
                    // echo $point['type_response'].': '.$point['count'].'<br>';
                    if($point['type_response'] == 'Exist, Good'){

                        $totalPoint += $point['count'] * 1;

                    }elseif($point['type_response'] == 'Exist Not Good'){
                        
                        $totalPoint += $point['count'] * -1;

                    }elseif($point['type_response'] == 'Not Exist'){
                        $totalPoint += $point['count'] * 0;
                    }
                endforeach;
                // $totalPoint = 0;

                if($totalPoint != 0){
                    $result = ($totalPoint / ($data_point->total_checklist - $data_point->checklist_remaining)) * 100;
                    $formattedResult = number_format((float)$result, 2, '.', '');
                }else{
                    $formattedResult = 0;
                }
                
                foreach($grading as $item):
                    if($formattedResult >= $item->bottom && $formattedResult <= $item->top){
                       
                        $result_audit = $item->result;
                        
                    }
                endforeach;
                // echo $totalPoint; //total Point
                // echo $formattedResult;// ini % result
                // echo $result_audit; //result Audit

                $mandator = "";
                foreach($data_point->mandatory as $man):
                    if($man['sgp'] != null){
                        $mandator = "Bronze";
                    }else{
                        if($man['gp'] != null){
                        $mandator = "Silver";
                            
                        }else{
                            if($man['p'] != null){
                            $mandator = "Gold";
                                
                            }else{
                                $mandator = "Platinum";
                            }
                        }
                    }
                endforeach;

                $result = "";
                if ($result_audit == "Platinum" && $mandator == "Platinum") {
                    $result = "Platinum";
                } elseif ($result_audit == "Platinum" && $mandator == "Gold") {
                    $result = "Gold";
                } elseif ($result_audit == "Platinum" && $mandator == "Silver") {
                    $result = "Silver";
                } elseif ($result_audit == "Platinum" && $mandator == "Bronze") {
                    $result = "Bronze";
                } elseif ($result_audit == "Gold" && $mandator == "Platinum") {
                    $result = "Gold";
                } elseif ($result_audit == "Gold" && $mandator == "Gold") {
                    $result = "Gold";
                } elseif ($result_audit == "Gold" && $mandator == "Silver") {
                    $result = "Silver";
                } elseif ($result_audit == "Gold" && $mandator == "Bronze") {
                    $result = "Bronze";
                } elseif ($result_audit == "Silver" && $mandator == "Platinum") {
                    $result = "Silver";
                } elseif ($result_audit == "Silver" && $mandator == "Gold") {
                    $result = "Silver";
                } elseif ($result_audit == "Silver" && $mandator == "Silver") {
                    $result = "Silver";
                } elseif ($result_audit == "Silver" && $mandator == "Bronze") {
                    $result = "Bronze";
                } else {
                    $result = "Bronze";
                }
                
                ChecklistJaringan::where('id', $data_point->id)->whereNotIn('status', ['6', '7'])->update([
                    'status' => 2,
                    'total_point' => $totalPoint,
                    'result_percentage' => $formattedResult,
                    'audit_result' => $result_audit,
                    'mandatory_item' => $mandator,
                    'result_final' => $result
                ]);

            endforeach;
            //setelah selesai update, update status Period nya
            // echo "status periode : 3";
            MstPeriodeChecklists::where('id', $id)->update([
                'status' => '3'
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
            // Recepient Email
            if($development == 1){
                $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                $ccemail = null;
            } else {
                $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                $ccemail = $emailsubmitter;
            }
            // Mail Content
            $mailInstance = new SubmitChecklist($periodinfo, $checklistdetail, $emailsubmitter);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailInstance);
            
            DB::commit();

            $this->auditLogsShort('Submit answer Checklist Period ('. $id . ')');
            
            return redirect()->route('formchecklist.typechecklist', encrypt($id))->with(['success' => 'Success Submit Your answer Checklist']);
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}

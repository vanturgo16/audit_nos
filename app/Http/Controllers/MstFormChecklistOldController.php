<?php

namespace App\Http\Controllers;

use App\Models\ChecklistResponse;
use App\Models\FileInputResponse;
use App\Models\MstAssignChecklists;
use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
// use Intervention\Image\Facades\Image;
use Browser;

//model
use App\Models\MstChecklists;
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
use DateTime;

// Mail
use App\Mail\SubmitChecklist;
use App\Models\ChecklistResponses;

use function PHPUnit\Framework\isEmpty;

class MstFormChecklistOldController extends Controller
{
    use AuditLogsTrait;
    public function form()
    {
        return view('formchecklist.form');
    }
    public function index(Request $request)
    {
        $datas = MstJaringan::get();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) {
                return view('formchecklist.action.index', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View Jaringan Checklist');
        
        return view('formchecklist.index',compact('datas'));
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

    public function periode_jaringan(Request $request, $id)
    {
        $id = decrypt($id);
        $datas = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
        ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
        ->orderBy('mst_periode_checklists.created_at')
        ->where('mst_dealers.id', $id)
        ->where('mst_periode_checklists.is_active', '1')
        ->get();

        $jaringan = MstJaringan::where('id', $id)->first()->dealer_name;

        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) {
                return view('formchecklist.action.period', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View Periode Form Checklist');

        return view('formchecklist.periode',compact('datas', 'id', 'jaringan'));

    }

    public function typechecklist(Request $request, $id)
    {
        $id = decrypt($id);//id Period

        //ForSortingBasedDropdown
        $sortdropdown = MstDropdowns::where('category', 'Type Checklist')->orderby('created_at')->pluck('name_value')->toArray();
        $datas = ChecklistJaringan::where('id_periode', $id)->orderByRaw("FIELD(type_checklist, '" . implode("','", $sortdropdown) . "')")->get();

        $period = MstPeriodeChecklists::where('id', $id)->first();
        $id_jaringan = MstPeriodeChecklists::where('id', $id)->first()->id_branch;

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
            $datam->decisionpic = $period->decisionpic;
        }

        $grading = MstGrading::all();
        $statusperiod = $period->status;

        $today = Carbon::today();
        $today = $today->format('Y-m-d');
        $startdate = $period->start_date;

        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) use ($grading, $statusperiod, $today, $startdate) {
                return view('formchecklist.action.typechecklist', compact('data', 'grading', 'statusperiod', 'today', 'startdate'));
            })
            ->toJson();

            return $data;
        }

        // $status = $datas->every(function ($item, $key) {

        //     return $item['status'] == 1;
        // });
        //kita cek apakah ada disalah satu angka 5, kalau ya true, 
        //kalau gak masuk ke kondisi kedua kalau ada satu dan tidak ada selain 1,5
        $status = $datas->contains('status', 5) || ($datas->contains('status', 1) && !$datas->contains(fn($item) => !in_array($item['status'], [1, 5]) && $item['status'] >= 0 && $item['status'] <= 9));
        // dd($status);
        // Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('formchecklist.typechecklist',compact('datas', 'period', 'grading', 'status', 'id', 'id_jaringan'));
    }

    public function startchecklist($id)
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

    public function checklistform($id, Request $request)
    {
        $id = decrypt($id); // id_checklist Jaringan

        $type = ChecklistJaringan::where('id', $id)->first();
        $datas = MstAssignChecklists::select(
                'mst_assign_checklists.id as id_assign', 
                'mst_assign_checklists.*', 
                'mst_assign_checklists.path_guide_parent as path_guide_premises', 
                'mst_assign_checklists.parent_point_checklist as parent_point', 
                'mst_assign_checklists.id_mst_checklist as id_checklist', 
                'checklist_jaringan.type_checklist',
            )
            ->Join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
            ->Join('checklist_jaringan', 'mst_periode_checklists.id', 'checklist_jaringan.id_periode')
            ->where('checklist_jaringan.id', $id)
            ->where('mst_assign_checklists.type_checklist', $type->type_checklist)
            ->get();

        $id_period = $type->id_periode;

        $point = MstAssignChecklists::select('parent_point_checklist as parent_point', 'path_guide_parent')
        ->where('id_periode_checklist', $id_period)
        ->where('type_checklist', $type->type_checklist)
        ->groupBy('parent_point_checklist', 'path_guide_parent')
        ->get();

        $period = MstPeriodeChecklists::where('id', $id_period)->first()->period;

        //Respons cheklist
        $respons = ChecklistResponse::select('checklist_response.*', 'mst_assign_checklists.parent_point_checklist as parent_point')
            ->Join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
            ->where('mst_assign_checklists.type_checklist', $type->type_checklist)
            ->where('mst_assign_checklists.id_periode_checklist', $id_period)
            ->get();

        //file point
        $file_point = FileInputResponse::select(
            'file_input_response.*',
            'trans_file_response.parent_point'
        )
        ->Join('trans_file_response', 'file_input_response.id_trans_file', 'trans_file_response.id')
        ->Join('mst_periode_checklists', 'trans_file_response.id_period', 'mst_periode_checklists.id')
        ->where('trans_file_response.type_checklist', $type->type_checklist)
        ->where('mst_periode_checklists.id', $id_period)
        ->get();


        //kita dapatkan parent_pointnya dengan kita count juga
        $parentCountsSoal = $datas->pluck('parent_point')->countBy();
        $parentCountsRespons = $respons->pluck('parent_point')->countBy();

        // Kita Coba Filter hanya parent yang ada dan nilai yang sama
        $ansfull = $parentCountsSoal->intersectAssoc($parentCountsRespons)->keys()->all();

        // tab
        $lastindex = 0;
        if($request->session()->has('tabo')){
            $tabo = intval($request->session()->get('tabo')) + 1;
            if($request->session()->has('additionalVariable')){
                $lastindex = 1;
            }
        }else{
            $tabo = 1;
        }

        //Auditlog
        $this->auditLogsShort('View Checklist Form:', $id);
        // dd($type->type_checklist);
        if($type->type_checklist == 'H1 Premises'){
            return view('formchecklist.form_h1_premises',compact('datas', 'id_period', 'type', 'id', 'point', 'period', 'respons', 'file_point', 'tabo', 'lastindex', 'ansfull'));
            // return view('formchecklist.checklist_h1premises_form',compact('datas', 'id_period', 'type', 'id', 'point', 'period', 'respons', 'file_point', 'tabo', 'lastindex', 'ansfull'));
        } else {
            return view('formchecklist.checklistform',compact('datas', 'id_period', 'type', 'id', 'point', 'period', 'respons', 'file_point', 'tabo', 'lastindex', 'ansfull'));
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
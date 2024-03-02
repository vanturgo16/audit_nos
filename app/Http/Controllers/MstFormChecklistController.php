<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use App\Models\ChecklistResponse;
use App\Models\MstAssignChecklists;
use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Intervention\Image\Facades\Image;
use Browser;

//model
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Models\MstJaringan;
use App\Models\MstParentChecklists;
use App\Models\MstPeriodeChecklists;
use Carbon\Carbon;
use DateTime;

class MstFormChecklistController extends Controller
{
    use AuditLogsTrait;
    public function form()
    {
        return view('formchecklist.form');
    }
    public function index(Request $request)
    {

        // $datas=MstChecklists::get();
        $datas = MstJaringan::get();

        //Audit Log
        $this->auditLogsShort('View Jaringan Checklist');
        
        return view('formchecklist.index',compact('datas'));
    }

    public function periode_jaringan($id)
    {
        $id = decrypt($id);
        $datas = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
        ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
        ->orderBy('mst_periode_checklists.created_at')
        ->where('mst_dealers.id', $id)
        ->where('mst_periode_checklists.is_active', '1')
        ->get();

        // dd($datas);

        //Audit Log
        $this->auditLogsShort('View Periode Form Checklist');

        return view('formchecklist.periode',compact('datas'));

    }
    public function typechecklist($id)
    {
        $id = decrypt($id);

        $datas = ChecklistJaringan::all()->where('id_periode', $id);


        //Audit Log
        $this->auditLogsShort('View Data Checklist, Period: ', $id);

        return view('formchecklist.typechecklist',compact('datas'));
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
    public function checklistform($id)
    {
        $id = decrypt($id);
        $type = ChecklistJaringan::where('id', $id)->first();
        $datas = MstAssignChecklists::select(
            'mst_assign_checklists.*', 
            'mst_checklists.*', 
            'mst_parent_checklists.path_guide_premises', 
            'mst_checklists.id as id_checklist', 
            'checklist_jaringan.type_checklist',
        )
        ->Join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
        ->Join('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
        ->Join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
        ->Join('checklist_jaringan', 'mst_periode_checklists.id', 'checklist_jaringan.id_periode')
        ->where('checklist_jaringan.id', $id)
        ->where('mst_parent_checklists.type_checklist', $type->type_checklist)
        ->get();
        $id_period = $type->id_periode;
        
        foreach ($datas as $data) {
            $checklistDetails = MstChecklistDetails::where('id_checklist', $data->id_checklist)->get()->toArray();
            $data->mark = $checklistDetails;
        }
        //Auditlog
        // dd($datas);
        $this->auditLogsShort('View Checklist Form:', $id);

        return view('formchecklist.checklistform',compact('datas', 'id_period', 'id'));

        
    }
    public function store($id, Request $request)
    {
        // $id = decrypt($id);
        // dd($request->all());
        DB::beginTransaction();
        try{
            $count = 0;
            foreach($request->except('_token', 'id_checklist_jaringan') as $key=>$value){
                $id_assign = (int)substr($key,strlen('question'));

                if($value != null){
                    $count++;
                    ChecklistResponse::create([
                        'id_assign_checklist' => $id_assign,
                        'response' => $value
                    ]);
                }   
                
            }
            //ngurangin data
            $get_total = ChecklistJaringan::where('id', $request->id_checklist_jaringan)->first();
            $remaining = $get_total->total_checklist - $count;
            if($remaining == 0){
                $status = 1;
            }else{
                $status = $get_total->status;
            }
            ChecklistJaringan::where('id', $request->id_checklist_jaringan)->update([
                'checklist_remaining' => $remaining,
                'status' => $status,
            ]);



            DB::commit();
            return redirect()->route('formchecklist.typechecklist', $id)->with(['success' => 'Success Update Checklist']);



        } catch (\Exception $e) {

            DB::rollBack();
            dd($e);
        }



    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $request->validate([
            'type_checklist' => 'required',
            'point_checklist' => 'required',
            'sub_point_checklist' => 'required',
            'indikator' => 'required',
            'mandatory_silver' => 'required',
            'mandatory_gold' => 'required',
            'mandatory_platinum' => 'required',
            'upload_file' => 'required'
        ]);


        $databefore = MstChecklists::where('id', $id)->first();
        $databefore->type_checklist = $request->type_checklist;
        $databefore->point_checklist = $request->point_checklist;
        $databefore->sub_point_checklist = $request->sub_point_checklist;
        $databefore->indikator = $request->indikator;
        $databefore->mandatory_silver = $request->mandatory_silver;
        $databefore->mandatory_gold = $request->mandatory_gold;
        $databefore->mandatory_platinum = $request->mandatory_platinum;
        $databefore->upload_file = $request->upload_file;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstChecklists::where('id', $id)->update([
                    'type_checklist' => $request->type_checklist,
                    'point_checklist' => $request->point_checklist,
                    'sub_point_checklist' => $request->sub_point_checklist,
                    'indikator' => $request->indikator,
                    'mandatory_silver' => $request->mandatory_silver,
                    'mandatory_gold' => $request->mandatory_gold,
                    'mandatory_platinum' => $request->mandatory_platinum,
                    'upload_file' => $request->upload_file
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Checklist';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Checklist']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Checklist!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function mark($id)
    {

        $id = decrypt($id);

        $datas = MstChecklistDetails ::where('id_checklist', $id)->get();
        $type_mark = MstDropdowns ::where('category', 'Type Mark Checklist')->get();
        $checklist=MstChecklists::select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist', 'mst_parent_checklists.path_guide_premises')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->where('mst_checklists.id', $id)
            ->first();

        //Audit Log
        $this->auditLogsShort('View List Mark Checklist ('. $checklist->parent_point_checklist . ')');

        return view('checklist.markcheck',compact('datas', 'type_mark', 'id', 'checklist'));
        // dd($datas);
    }
    
    public function markstore(Request $request, $id)
    {
        $id = decrypt($id);

        $request->validate([
            'meta_name' => 'required|array|min:1',
            'meta_name.*' => 'exists:mst_dropdowns,id',
        ]);

        
        
        DB::beginTransaction();
        try{
            

            foreach ($request->meta_name as $idmetaName) {

                $mark = MstDropdowns::where('id', $idmetaName)->first();
                $valueName = $mark->name_value;
                $codeFormat = $mark->code_format;
                
        
                MstChecklistDetails::firstOrCreate([
                    'id_checklist' => $id,
                    'meta_name' => $valueName,
                ], [
                    'result' => $codeFormat,
                    'meta_value' => '1'
                ]);
            }

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Mark Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Mark Checklist']);
        }catch (\Exception $e) {
        
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Mark Checklist!']);
        }
    }

    public function markdelete($id)
    {
        $id = decrypt($id);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $data = MstChecklistDetails::findOrFail($id)->delete();
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Mark Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);


            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Mark Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Mark Checklist!']);
        }
        
    }

}

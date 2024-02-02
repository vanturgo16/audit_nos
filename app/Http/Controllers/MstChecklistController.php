<?php

namespace App\Http\Controllers;

use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

//model
use App\Models\MstChecklists;
use App\Models\MstDropdowns;

class MstChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {

        $datas=MstChecklists::get();
        $type_checklist = MstDropdowns ::where('category', 'Type Checklist')->get();

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Checklist';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('checklist.index',compact('datas', 'type_checklist'));
        // dd($datas);
    }
    public function store(Request $request)
    {

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

        DB::beginTransaction();
        try{
            
            MstChecklists::create([
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
            $activity='Create New Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Checklist!']);
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

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mark Checklist';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('checklist.markcheck',compact('datas', 'type_mark', 'id'));
        // dd($datas);
    }

    public function markstore(Request $request, $id)
    {
        $id = decrypt($id);
        $request->validate([
            'meta_name' => 'required',
            'result' => 'required'
        ]);

        DB::beginTransaction();
        try{
            
            MstChecklistDetails::create([
                'id_checklist' => $id,
                'meta_name' => $request->meta_name,
                'result' => $request->result,
                'meta_value' => '1'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Mark Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Mark Checklist']);
        } catch (\Exception $e) {
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

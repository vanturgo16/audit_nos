<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstPositions;
use App\Models\MstDepartments;

class MstPositionController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $datas=MstPositions::select('mst_positions.*', 'mst_departments.department_name as department')
            ->leftjoin('mst_departments', 'mst_positions.id_department', 'mst_departments.id')
            ->get();

        $departments=MstDepartments::where('is_active', 1)->get();
        $alldepartments=MstDepartments::get();

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Position';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('position.index',compact('datas', 'departments', 'alldepartments'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'id_department' => 'required',
            'position_name' => 'required',
        ]);

        DB::beginTransaction();
        try{
            
            MstPositions::create([
                'id_department' => $request->id_department,
                'position_name' => $request->position_name,
                'is_active' => '1'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Position';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Position']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Position!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'id_department' => 'required',
            'position_name' => 'required',
        ]);

        $databefore = MstPositions::where('id', $id)->first();
        $databefore->id_department = $request->id_department;
        $databefore->position_name = $request->position_name;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstPositions::where('id', $id)->update([
                    'id_department' => $request->id_department,
                    'position_name' => $request->position_name
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Position';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Position']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Position!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstPositions::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstPositions::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Position ('. $name->position_name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Position ' . $name->position_name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Position ' . $name->position_name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstPositions::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstPositions::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Position ('. $name->position_name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Position ' . $name->position_name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Position ' . $name->position_name .'!']);
        }
    }

    public function json_position($id)
    {
        $positions = MstPositions::where('id_department', $id)->get();

        // dd($positions);
        return response()->json($positions);
    }
}

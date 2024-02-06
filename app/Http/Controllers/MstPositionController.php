<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstPositions;
use App\Models\MstDepartments;

class MstPositionController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $departments=MstDepartments::where('is_active', 1)->get();

        if ($request->ajax()) {
            $data = $this->getData($departments);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Position');
        
        return view('position.index',compact('departments'));
    }

    private function getData($departments)
    {
        $query=MstPositions::select('mst_positions.*', 'mst_departments.department_name as department')
            ->leftjoin('mst_departments', 'mst_positions.id_department', 'mst_departments.id')
            ->orderBy('mst_positions.created_at')
            ->get();

        $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($departments){
                return view('position.action', compact('data', 'departments'));
            })
            ->toJson();

        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'id_department' => 'required',
            'position_name' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        DB::beginTransaction();
        try{
            
            MstPositions::create([
                'id_department' => $request->id_department,
                'position_name' => $request->position_name,
                'is_active' => '1'
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Position');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Position']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Position!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'id_department' => 'required',
            'position_name' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

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
                $this->auditLogsShort('Update Position');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Position']);
            } catch (Exception $e) {
                DB::rollback();
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
            $this->auditLogsShort('Activate Position ('. $name->position_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Position ' . $name->position_name]);
        } catch (Exception $e) {
            DB::rollback();
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
            $this->auditLogsShort('Deactivate Position ('. $name->position_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Position ' . $name->position_name]);
        } catch (Exception $e) {
            DB::rollback();
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

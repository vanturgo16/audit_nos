<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstDepartments;

class MstDepartmentController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Department');
        
        return view('department.index');
    }

    private function getData()
    {
        $query = MstDepartments::orderBy('created_at')->get();
        $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('department.action', compact('data'));
            })
            ->toJson();
        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'department_name' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        DB::beginTransaction();
        try{
            
            MstDepartments::create([
                'department_name' => $request->department_name,
                'is_active' => '1'
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Department');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Department']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Department!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'department_name' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $databefore = MstDepartments::where('id', $id)->first();
        $databefore->department_name = $request->department_name;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstDepartments::where('id', $id)->update([
                    'department_name' => $request->department_name
                ]);

                //Audit Log
                $this->auditLogsShort('Update Department');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Department']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Department!']);
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
            MstDepartments::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstDepartments::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Department ('. $name->department_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Department ' . $name->department_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Department ' . $name->department_name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstDepartments::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstDepartments::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Department ('. $name->department_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Department ' . $name->department_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Department ' . $name->department_name .'!']);
        }
    }
}

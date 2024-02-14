<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstRules;

class MstRuleController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Rules');
        
        return view('rule.index');
    }

    private function getData()
    {
        $query = MstRules::orderBy('created_at')->get();
        $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('rule.action', compact('data'));
            })
            ->toJson();
        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        DB::beginTransaction();
        try{
            MstRules::create([
                'rule_name' => $request->rule_name,
                'rule_value' => $request->rule_value,
                'is_active' => '1'
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Rule');
            
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Rule']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Rule!'])->withErrors($e->getMessage());;
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $databefore = MstRules::where('id', $id)->first();
        $databefore->rule_name = $request->rule_name;
        $databefore->rule_value = $request->rule_value;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstRules::where('id', $id)->update([
                    'rule_name' => $request->rule_name,
                    'rule_value' => $request->rule_value
                ]);

                //Audit Log
                $this->auditLogsShort('Update Rule');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Rule']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Rule!']);
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
            MstRules::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstRules::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Rule ('. $name->rule_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Rule ' . $name->rule_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Rule ' . $name->rule_name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstRules::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstRules::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Rule ('. $name->rule_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Rule ' . $name->rule_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Rule ' . $name->rule_name .'!']);
        }
    }
}

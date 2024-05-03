<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstPeriodName;

class MstPeriodNameController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstPeriodName::get();
        
        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) {
                return view('periodname.action', compact('data'));
            })
            ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Period Name');
        
        return view('periodname.index',compact('datas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'period_name' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $check = MstPeriodName::where('period_name', $request->period_name)->first();
        if($check != null){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Period Name Is Exist']);
        }

        DB::beginTransaction();
        try{
            MstPeriodName::create([
                'period_name' => $request->period_name
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Period Name');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Period']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Period!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);
        $validate = Validator::make($request->all(),[
            'period_name' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $databefore = MstPeriodName::where('id', $id)->first();
        $check = MstPeriodName::where('period_name', '!=', $databefore->period_name)->where('period_name', $request->period_name)->first();
        if($check != null){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Period Name Is Exist']);
        }
        $databefore->period_name = $request->period_name;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstPeriodName::where('id', $id)->update([
                    'period_name' => $request->period_name
                ]);

                //Audit Log
                $this->auditLogsShort('Update Period Name');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Period']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Period!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
}

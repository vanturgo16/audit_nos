<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstAssignChecklists;
use App\Models\MstChecklists;
use App\Models\MstJaringan;
use App\Models\MstParentChecklists;
use App\Models\MstPeriodeChecklists;

class MstAssignChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $period=MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();

        $checklists=MstChecklists::select('mst_checklists.id as id_checklist', 'mst_checklists.*', 'mst_parent_checklists.*')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->get();
        $assign = MstAssignChecklists::where('id_periode_checklist', $id)->first();
        if($assign == null){
            $check = 0;
        }else{
            $check = 1;
        }

        if ($request->ajax()) {
            $data = $this->getData($period, $checklists);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Assign Checklist ('. $period->period . ')');
        
        return view('assignchecklist.index',compact('period', 'checklists', 'check'));
    }

    private function getData($period, $checklists)
    {
        $query = MstAssignChecklists::select('mst_assign_checklists.id as id_assign_checklist', 'mst_assign_checklists.*', 'mst_periode_checklists.period', 'mst_checklists.*', 'mst_parent_checklists.*')
            ->leftjoin('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
            ->leftjoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_periode_checklists.id', $period->id)
            ->orderBy('mst_assign_checklists.created_at')
            ->get();
            

        $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($period, $checklists){
                return view('assignchecklist.action', compact('data', 'period', 'checklists'));
            })
            ->toJson();

        return $data;
    }

    public function store(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);
        $validate = Validator::make($request->all(),[
            'id_mst_checklist' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        //Check
        $check = MstAssignChecklists::where('id_periode_checklist', $id)->where('id_mst_checklist', $request->id_mst_checklist)->count();
        if($check > 0){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Choose Different Checklist']);
        }

        DB::beginTransaction();
        try{
            
            MstAssignChecklists::create([
                'id_periode_checklist' => $id,
                'id_mst_checklist' => $request->id_mst_checklist,
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Assign Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Assign Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Assign Checklist!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            MstAssignChecklists::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Assign Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Assign Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Assign Checklist']);
        }
    }
    public function searchchecklist($id)
    {
        $data = MstChecklists::where('id', $id)->first();

        $data = $data->toArray();
        return response()->json($data);
    }
    public function submit($id)
    {
        $id = decrypt($id);
        $datas = MstAssignChecklists::select('mst_parent_checklists.type_checklist')
        ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
        ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
        ->where('mst_assign_checklists.id_periode_checklist', $id)
        ->groupBy('mst_parent_checklists.type_checklist')
        ->get();

       

        foreach ($datas as $data) {
            $count = MstAssignChecklists::Join('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
            ->Join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_assign_checklists.id_periode_checklist', $id)
            ->where('mst_parent_checklists.type_checklist', $data->type_checklist)->count();
            $data->countt = $count;
        }
        DB::beginTransaction();
        try{

            MstPeriodeChecklists::where('id', $id)->update([
                'is_active' => '1'
            ]);

            foreach($datas as $data){
    
                    ChecklistJaringan::create([
                        'id_periode' => $id,
                        'type_checklist' => $data->type_checklist,
                        'total_checklist' => $data->countt,
                        'checklist_remaining' => $data->countt,
                    ]);         
                
            }
            //Audit Log
            $this->auditLogsShort('Create New Assign Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Assign Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Assign Checklist!']);
        }
        
    }
}

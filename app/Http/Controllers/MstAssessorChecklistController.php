<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use App\Models\ChecklistResponse;
use App\Models\FileInputResponse;
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use App\Models\MstGrading;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;

class MstAssessorChecklistController extends Controller
{
    use AuditLogsTrait;

    public function listjaringan(Request $request)
    {
        if ($request->ajax()) {
            $query = MstJaringan::get();

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('assessor.listjaringan.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Jaringan in Assessor Checklist');
        
        return view('assessor.listjaringan.index');
    }

    public function listperiod(Request $request, $id)
    {
        $id = decrypt($id);

        $jaringan = MstJaringan::where('id', $id)->first();

        if ($request->ajax()) {
            $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->orderBy('mst_periode_checklists.created_at')
                ->where('mst_dealers.id', $id)
                ->where('mst_periode_checklists.is_active', '1')
                ->get();

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) {
                return view('assessor.listperiod.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Period in Assessor Checklist');
        
        return view('assessor.listperiod.index', compact('jaringan'));
    }

    public function typechecklist(Request $request, $id)
    {
        $id = decrypt($id);

        $period = MstPeriodeChecklists::where('id', $id)->first();
        
        $checks = ChecklistJaringan::select('status')->where('id_periode', $id)->get();
        $check = 0;

        foreach ($checks as $checkItem) {
            if ($checkItem->status == 2 || $period->status == 5) {
                $check = 1;
                break;
            }
        }

        if ($request->ajax()) {
            $datas = ChecklistJaringan::all()->where('id_periode', $id);

            foreach($datas as $data){

                $responsCounts = ChecklistResponse::select('checklist_response.response as response')
                ->join('mst_assign_checklists', 'checklist_response.id_assign_checklist', 'mst_assign_checklists.id')
                ->join('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                ->join('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->join('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
                ->join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                ->where('mst_parent_checklists.type_checklist', $data->type_checklist)
                ->where('mst_periode_checklists.id', $id)
                ->groupBy('checklist_response.response')
                ->selectRaw('checklist_response.response as type_response, COUNT(*) as count')
                ->get()->toArray();
                $data->total_point_arr = $responsCounts;

            }

            $grading = MstGrading::all();
            $status = $datas->every(function ($item, $key) {
                return $item['status'] == 1;
            });

            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) {
                return view('assessor.typechecklist.action', compact('data'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist in Assessor Checklist');
        
        return view('assessor.typechecklist.index', compact('period', 'check'));
    }

    public function review(Request $request, $id)
    {
        $id = decrypt($id);
        
        $type = ChecklistJaringan::where('id', $id)->first();

        //file point
        $file_point = FileInputResponse::select(
            'file_input_response.*',
            'mst_parent_checklists.parent_point_checklist as parent_point'
        )
        ->Join('trans_file_response', 'file_input_response.id_trans_file', 'trans_file_response.id')
        ->Join('mst_periode_checklists', 'trans_file_response.id_period', 'mst_periode_checklists.id')
        ->Join('mst_parent_checklists', 'trans_file_response.id_parent', 'mst_parent_checklists.id')
        ->where('mst_parent_checklists.type_checklist', $type->type_checklist)
        ->where('mst_periode_checklists.id', $type->id_periode)
        ->get();

        // dd($file_point);

        if ($request->ajax()) {
            $query = MstAssignChecklists::select(
                'mst_assign_checklists.id as id_assign', 
                'mst_assign_checklists.id_periode_checklist as id_periode_checklist', 
                'mst_checklists.*',
                'mst_parent_checklists.path_guide_premises', 
                'mst_parent_checklists.parent_point_checklist as parent_point', 
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

            foreach ($query as $q) {
                $response = ChecklistResponse::where('id_assign_checklist', $q->id_assign)->first()->response;
                $q->response = $response;
            }

            $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($file_point) {
                return view('assessor.review.action', compact('data', 'file_point'));
            })
            ->toJson();

            return $data;
        }

        //Audit Log
        $this->auditLogsShort('Review in Assessor Checklist');
        
        return view('assessor.review.index', compact('type'));
    }
    public function submitreview(Request $request, $id)
    {
        $id = decrypt($id);

        if($request->decission == 'approved'){
            $status = 4;
        } else {
            $status = 3;
        }

        DB::beginTransaction();
        try{
            
            $update = ChecklistJaringan::where('id', $id)->where('type_checklist', $request->typechecklist)->update([
                'status' => $status
            ]);

            //Audit Log
            $this->auditLogsShort('Submit Review Checklist');

            DB::commit();
            return redirect()->route('assessor.typechecklist', encrypt($request->idperiod))->with(['success' => 'Success Submit Decission']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Submit Checklist!']);
        }
    }

    public function finishreview(Request $request, $id)
    {
        $id = decrypt($id);

        $statuscheklist = ChecklistJaringan::select('status')->where('id_periode', $id)->get();
        $status = 4;
        foreach ($statuscheklist as $checkItem) {
            if ($checkItem->status == 3) {
                $status = 5;
                break;
            }
        }

        DB::beginTransaction();
        try{
            
            $update = MstPeriodeChecklists::where('id', $id)->update([
                'status' => $status
            ]);

            //Audit Log
            $this->auditLogsShort('Finish Review Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Finish Review']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Finish Checklist!']);
        }
    }
}

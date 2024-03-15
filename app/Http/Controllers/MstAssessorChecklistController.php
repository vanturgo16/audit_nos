<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use App\Models\ChecklistResponse;
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
                $data->total_point = $responsCounts;

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
        
        return view('assessor.typechecklist.index', compact('period'));
    }

    private function getData($branchs, $period_name)
    {
        $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->orderBy('mst_periode_checklists.created_at')
            ->get();

        $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($branchs, $period_name) {
                return view('periodchecklist.action', compact('data', 'branchs', 'period_name'));
            })
            ->toJson();
        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'period' => 'required',
            'id_branch' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        DB::beginTransaction();
        try{
            
            MstPeriodeChecklists::create([
                'period' => $request->period,
                'id_branch' => $request->id_branch,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => '0',
                'status' => '0'
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Period Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Period Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Period Checklist!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'period' => 'required',
            'id_branch' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $databefore = MstPeriodeChecklists::where('id', $id)->first();
        $databefore->period = $request->period;
        $databefore->id_branch = $request->id_branch;
        $databefore->start_date = $request->start_date;
        $databefore->end_date = $request->end_date;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstPeriodeChecklists::where('id', $id)->update([
                    'period' => $request->period,
                    'id_branch' => $request->id_branch,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

                //Audit Log
                $this->auditLogsShort('Update Period Checklist');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Period Checklist']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Period Checklist!']);
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
            MstPeriodeChecklists::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstPeriodeChecklists::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Period Checklist ('. $name->period . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Period Checklist ' . $name->period]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Period Checklist ' . $name->period .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstPeriodeChecklists::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstPeriodeChecklists::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Period Checklist ('. $name->period . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Period Checklist ' . $name->period]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Period Checklist ' . $name->period .'!']);
        }
    }
}

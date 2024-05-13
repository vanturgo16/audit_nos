<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

// Model
use App\Models\MstAssignChecklists;
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Models\MstJaringan;
use App\Models\MstParentChecklists;
use App\Models\MstPeriodeChecklists;
use App\Models\MstRules;

// Mail
use App\Mail\SubmitAssignChecklist;
use App\Models\MstChecklistDetails;

class MstAssignChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $period = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name','mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();

        $assign = MstAssignChecklists::where('id_periode_checklist', $id)->first();
        $check = ($assign == null) ? 0 : 1;

        if ($request->ajax()) {
            $data = $this->getData($period);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Assign Checklist ('. $period->period . ')');
        
        return view('assignchecklist.index',compact('period', 'check'));
    }

    private function getData($period)
    {
        $typechecklist = MstDropdowns::select('name_value')->where('category', 'Type Checklist')->get();
        foreach($typechecklist as $type){
            // Period Has Assign or Not
            if($period->is_active == 0){
                $count_check = MstAssignChecklists::leftjoin('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                    ->leftjoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
                    ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                    ->where('mst_periode_checklists.id', $period->id)
                    ->where('mst_parent_checklists.type_checklist', $type->name_value)
                    ->count();
                $count = MstAssignChecklists::leftJoin('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
                    ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
                    ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                    ->where('mst_periode_checklists.id', $period->id)
                    ->where('mst_parent_checklists.type_checklist', $type->name_value)
                    ->groupBy('mst_checklists.id_parent_checklist')
                    ->select('mst_checklists.id_parent_checklist', \DB::raw('COUNT(*) as count'))
                    ->get()
                    ->count();
            } else {
                // If Has Assign Take From Assign Checklist
                $count_check = MstAssignChecklists::where('id_periode_checklist', $period->id)->where('type_checklist', $type->name_value)->count();
                $count = MstAssignChecklists::where('id_periode_checklist', $period->id)->where('type_checklist', $type->name_value)
                    ->groupBy('parent_point_checklist')
                    ->select('parent_point_checklist', \DB::raw('COUNT(*) as count'))
                    ->count();
            }

            $type->count_check = $count_check;
            $type->count = $count;
        }

        $data = DataTables::of($typechecklist)
            ->addColumn('action', function ($data) use ($period){
                return view('assignchecklist.action', compact('data', 'period'));
            })
            ->toJson();

        return $data;
    }

    public function type(Request $request, $id, $type)
    {
        $id = decrypt($id);

        $period=MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
        $checklists=MstChecklists::select('mst_checklists.id as id_checklist', 'mst_checklists.*', 'mst_parent_checklists.*')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_parent_checklists.type_checklist', $type)
            ->get();

        if($period->is_active == 0){
            $query = MstAssignChecklists::select('mst_assign_checklists.id as id_assign_checklist', 'mst_assign_checklists.*', 'mst_periode_checklists.period', 'mst_checklists.*', 'mst_parent_checklists.*')
            ->leftjoin('mst_periode_checklists', 'mst_assign_checklists.id_periode_checklist', 'mst_periode_checklists.id')
            ->leftjoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_periode_checklists.id', $id)
            ->where('mst_parent_checklists.type_checklist', $type)
            ->orderBy('mst_assign_checklists.created_at')
            ->get();
        } else {
            $query = MstAssignChecklists::select('mst_assign_checklists.ms as mandatory_silver','mst_assign_checklists.mg as mandatory_gold','mst_assign_checklists.mp as mandatory_platinum', 'mst_assign_checklists.*')
            ->where('id_periode_checklist', $id)->where('type_checklist', $type)
            ->get();
        }

        if ($request->ajax()) {
            $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($period, $checklists){
                return view('assignchecklist.type.action', compact('data', 'period', 'checklists'));
            })
            ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Assign Checklist ('. $period->period . ') type: '. $type);

        return view('assignchecklist.type.index',compact('period', 'checklists', 'type'));
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

        $assignchecklist = MstAssignChecklists::where('id_periode_checklist', $id)->get();

        DB::beginTransaction();
        try{
            // Update Assign Checklist With Detail Checklist, (So, When Master Checklist Update This Assign Not Affect)
            foreach($assignchecklist as $assign){
                $detailchecklist = MstAssignChecklists::select('mst_checklists.id as id_checklist', 'mst_parent_checklists.*', 'mst_checklists.*')
                    ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
                    ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                    ->where('mst_checklists.id', $assign->id_mst_checklist)
                    ->first();
                $mark = MstChecklistDetails::select('id', 'meta_name')->where('id_checklist', $assign->id_mst_checklist)->get()->toArray();
                // Check IF Any Checklist Doesnt Have Mark
                if($mark == null){
                    return redirect()->back()->with(['fail' => 'Failed, Checklist = '.$detailchecklist->sub_point_checklist.' Dont Have Any Mark Yet!, Please Update The Checklist']);
                }
                // Update Assign Master
                $update_assign = MstAssignChecklists::where('id', $assign->id)->update([
                    'type_checklist' => $detailchecklist->type_checklist,
                    'parent_point_checklist' => $detailchecklist->parent_point_checklist,
                    'path_guide_parent' => $detailchecklist->path_guide_premises,
                    'child_point_checklist' => $detailchecklist->child_point_checklist,
                    'sub_point_checklist' => $detailchecklist->sub_point_checklist,
                    'indikator' => $detailchecklist->indikator,
                    'ms' => $detailchecklist->mandatory_silver,
                    'mg' => $detailchecklist->mandatory_gold,
                    'mp' => $detailchecklist->mandatory_platinum,
                    'upload_file' => $detailchecklist->upload_file,
                    'path_guide_checklist' => $detailchecklist->path_guide_checklist,
                    'mark' => json_encode($mark)
                ]);
            }
            
            MstPeriodeChecklists::where('id', $id)->update([
                'is_active' => '1',
                'status' => '1'
            ]);

            foreach($datas as $data){
                ChecklistJaringan::create([
                    'id_periode' => $id,
                    'type_checklist' => $data->type_checklist,
                    'total_checklist' => $data->countt,
                    'checklist_remaining' => $data->countt,
                ]);   
            }
            
            // [ MAILING ]
            // Initiate Variable
            $emailsubmitter = auth()->user()->email;
            $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
            $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                ->where('mst_periode_checklists.id', $id)
                ->first();
            $count = MstAssignChecklists::where('id_periode_checklist', $id)->count();
            $periodinfo->count = $count;
            $checklistdetail = ChecklistJaringan::where('id_periode', $id)->get();
            // Recepient Email
            if($development == 1){
                $toemail = MstRules::where('rule_name', 'Email Development')->first()->rule_value;
                $ccemail = null;
            } else {
                $toemail = MstPeriodeChecklists::leftJoin('mst_employees', 'mst_periode_checklists.id_branch', 'mst_employees.id_dealer')
                    ->leftJoin('users', 'mst_employees.email', 'users.email')
                    ->where('mst_periode_checklists.id', $id)
                    ->where('users.role', 'Internal Auditor Dealer')
                    ->pluck('mst_employees.email')->toArray();
                $ccemail = $emailsubmitter;
            }
            // Mail Content
            $mailInstance = new SubmitAssignChecklist($periodinfo, $checklistdetail, $emailsubmitter);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailInstance);

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

<?php

namespace App\Http\Controllers;

use App\Models\MstAssignChecklists;
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;
use App\Models\MstMapChecklists;

class MstPeriodChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $branchs = MstJaringan::get();
        $period_name = MstDropdowns::where('category', 'Period Name')->get();

        if ($request->ajax()) {
            $data = $this->getData($branchs, $period_name);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Period Checklist');
        
        return view('periodchecklist.index', compact('branchs', 'period_name'));
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

            // kita buat dulu periodenya
            $newPeriode = MstPeriodeChecklists::create([
                            'period' => $request->period,
                            'id_branch' => $request->id_branch,
                            'start_date' => $request->start_date,
                            'end_date' => $request->end_date,
                            'is_active' => '0',
                            'status' => '0'
                        ]);
            $IdPeriod = $newPeriode->id;

            //lalu kita dapatkan type jaringannya untuk assign checklist
            $branchs = MstJaringan::where('id', $request->id_branch)->first()->type;

            //setelah type jaringan didapatkan, kita lakukan pengecekan ke Mapping Checklist
            // dapatkan id_parent
            $mapcheck = MstMapChecklists::select('id_parent_checklist')
            ->where('type_jaringan', $branchs)->get();
            
            // id_parent didapatkan , lakukan perulangan untuk mendapatkan id_checklist
            foreach($mapcheck as $map){

                // echo 'id_parent : '.$map['id_parent_checklist'].'<br>';
                $checklist = MstChecklists::select('id')
                ->where('id_parent_checklist', $map['id_parent_checklist'])->get();

                $no = 1;
                //id_checklist didapatkan
                foreach($checklist as $check){

                    // echo 'id_checklist'.$no++.' : '.$check['id'].'<br>';
                    //create assign dengan id_checklist yang didapat
                    MstAssignChecklists::create([
                        'id_periode_checklist' => $IdPeriod,
                        'id_mst_checklist' => $check['id']
                    ]);

                }
                
                $no = 1;
    
            }
            
            //Audit Log
            $this->auditLogsShort('Create New Period Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Period Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Period Checklist!']);
        }
    }
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validate = Validator::make($request->all(),[
    //         'period' => 'required',
    //         'id_branch' => 'required',
    //         'start_date' => 'required',
    //         'end_date' => 'required'
    //     ]);
    //     if($validate->fails()){
    //         return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
    //     }

    //     DB::beginTransaction();
    //     try{
            
    //         MstPeriodeChecklists::create([
    //             'period' => $request->period,
    //             'id_branch' => $request->id_branch,
    //             'start_date' => $request->start_date,
    //             'end_date' => $request->end_date,
    //             'is_active' => '0',
    //             'status' => '0'
    //         ]);

    //         //Audit Log
    //         $this->auditLogsShort('Create New Period Checklist');

    //         DB::commit();
    //         return redirect()->back()->with(['success' => 'Success Create New Period Checklist']);
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()->with(['fail' => 'Failed to Create New Period Checklist!']);
    //     }
    // }

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

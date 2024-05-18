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
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;
use App\Models\MstMapChecklists;
use App\Models\ChecklistJaringan;
use App\Models\MstRules;
use App\Models\MstPeriodName;

// Mail
use App\Mail\UpdateExpired;

class MstPeriodChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $branchs = MstJaringan::get();
        $period_name = MstPeriodName::get();

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

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $today = Carbon::today();

        if ($end_date < $start_date) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Start Date Must Be Earlier Than End Date']);
        } 
        if ($start_date < $today) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill Start Date Less as Today']);
        } 
        if ($end_date <= $today) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill End Date Less or Same as Today']);
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

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $today = Carbon::today();

        if ($end_date < $start_date) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Start Date Must Be Earlier Than End Date']);
        } 
        if ($start_date < $today) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill Start Date Less as Today']);
        } 
        if ($end_date <= $today) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill End Date Less or Same as Today']);
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

    public function updateexpired(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $validate = Validator::make($request->all(),[
            'end_date' => 'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $end_date = Carbon::parse($request->end_date);
        $today = Carbon::today();

        if ($end_date <= $today) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill End Date Less or Same as Today']);
        } 

        DB::beginTransaction();
        try{
            MstPeriodeChecklists::where('id', $id)->update([
                'end_date' => $request->end_date,
                'status' => 1,
            ]);

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
                $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
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
            $mailInstance = new UpdateExpired($periodinfo, $checklistdetail, $emailsubmitter);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailInstance);

            //Audit Log
            $this->auditLogsShort('Update Expired Period Checklist ID:'.$id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Update Expired Period Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Update Expired Period Checklist!']);
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

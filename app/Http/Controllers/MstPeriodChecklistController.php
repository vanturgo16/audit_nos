<?php

namespace App\Http\Controllers;

use App\Models\MstAssignChecklists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\MailingTrait;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstJaringan;
use App\Models\MstMapChecklists;
use App\Models\ChecklistJaringan;
use App\Models\MstPeriodName;

// Mail
use App\Mail\UpdateExpired;
use App\Models\MstEmployees;

class MstPeriodChecklistController extends Controller
{
    use AuditLogsTrait;
    use MailingTrait;

    public function index(Request $request)
    {
        $branchs = MstJaringan::get();
        $period_name = MstPeriodName::orderby('created_at', 'desc')->get();

        $query = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id');

        if ($request->has('filterBranch') && $request->filterBranch != '' && $request->filterBranch != 'All') {
            $query->where('mst_periode_checklists.id_branch', $request->filterBranch);
        }

        $query = $query->orderBy('mst_periode_checklists.created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('action', function ($data) use ($branchs, $period_name) {
                    return view('periodchecklist.action', compact('data', 'branchs', 'period_name'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Period Checklist');

        return view('periodchecklist.index', compact('branchs', 'period_name'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required',
            'id_branch' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $today = Carbon::today();
        if ($end_date < $start_date) {
            $message = 'Failed, Start Date Must Be Earlier Than End Date';
        } elseif ($start_date < $today) {
            $message = 'Failed, You Cannot Fill Start Date Less as Today';
        } elseif ($end_date <= $today) {
            $message = 'Failed, You Cannot Fill End Date Less or Same as Today';
        }
        if (isset($message)) {
            return redirect()->back()->withInput()->with(['fail' => $message]);
        }

        DB::beginTransaction();
        try {
            $period = MstPeriodeChecklists::create([
                'period' => $request->period,
                'id_branch' => $request->id_branch,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => auth()->user()->email,
                'is_active' => 1,
                'status' => 0
            ]);

            // Get Mapping Checklist
            $type = MstJaringan::where('id', $request->id_branch)->first()->type;
            $mapCheck = MstMapChecklists::select('mst_checklists.id')
                ->leftjoin('mst_checklists', 'mst_mapchecklists.id_parent_checklist', 'mst_checklists.id_parent_checklist')
                ->where('mst_mapchecklists.type_jaringan', $type)
                ->get();
            foreach ($mapCheck as $item) {
                if($item->id){
                    MstAssignChecklists::create([
                        'id_periode_checklist' => $period->id,
                        'id_mst_checklist' => $item->id
                    ]);
                }
            }

            //Log Period
            $this->storeLogPeriod($period->id, 0, 'Initiate');

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
        $id = decrypt($id);

        $request->validate([
            'period' => 'required',
            'id_branch' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $today = Carbon::today();
        if ($end_date < $start_date) {
            $message = 'Failed, Start Date Must Be Earlier Than End Date';
        } elseif ($start_date < $today) {
            $message = 'Failed, You Cannot Fill Start Date Less as Today';
        } elseif ($end_date <= $today) {
            $message = 'Failed, You Cannot Fill End Date Less or Same as Today';
        }
        if (isset($message)) {
            return redirect()->back()->withInput()->with(['fail' => $message]);
        }

        $databefore = MstPeriodeChecklists::where('id', $id)->first();
        $databefore->period = $request->period;
        $databefore->id_branch = $request->id_branch;
        $databefore->start_date = $request->start_date;
        $databefore->end_date = $request->end_date;

        if ($databefore->isDirty()) {
            DB::beginTransaction();
            try {
                // IF Jaringan Update
                $databefore = MstPeriodeChecklists::where('id', $id)->first();
                if ($databefore->id_branch != $request->id_branch) {
                    // Delete Assign Before
                    MstAssignChecklists::where('id_periode_checklist', $id)->delete();
                    // Get Mapping Checklist
                    $type = MstJaringan::where('id', $request->id_branch)->first()->type;
                    $mapCheck = MstMapChecklists::select('mst_checklists.id')
                        ->leftjoin('mst_checklists', 'mst_mapchecklists.id_parent_checklist', 'mst_checklists.id_parent_checklist')
                        ->where('mst_mapchecklists.type_jaringan', $type)
                        ->get();
                    foreach ($mapCheck as $item) {
                        if ($item->id) {
                            MstAssignChecklists::create([
                                'id_periode_checklist' => $id,
                                'id_mst_checklist' => $item->id
                            ]);
                        }
                    }
                }

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
        $id = decrypt($id);

        // Validation
        $request->validate([
            'end_date' => 'required'
        ]);
        if (Carbon::parse($request->end_date) <= Carbon::today()) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Cannot Fill End Date Less or Same as Today']);
        }

        DB::beginTransaction();
        try {
            MstPeriodeChecklists::where('id', $id)->update([
                'end_date' => $request->end_date,
                'is_active' => 1,
            ]);

            // [ INITIATE VARIABLE ] 
            $variableEmail = $this->variableEmail();
            $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type', DB::raw('(SELECT COUNT(*) FROM mst_assign_checklists WHERE mst_assign_checklists.id_periode_checklist = mst_periode_checklists.id) as totalChecklist'))
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
                ->where('mst_periode_checklists.id', $id)
                ->first();
            $emailAuditor = MstEmployees::leftjoin('users', 'users.email', 'mst_employees.email')->where('mst_employees.id_dealer', $periodInfo->id_branch)->where('users.role', 'Internal Auditor Dealer')->pluck('users.email');
            if ($emailAuditor->isEmpty()) {
                return redirect()->back()->with(['fail' => 'Failed, Data Employee Internal Auditor Jaringan "' . $periodInfo->dealer_name . '" Not Exist']);
            }
            $checklistDetail = ChecklistJaringan::where('id_periode', $id)->get();
            // Recepient Email
            if ($variableEmail['devRule'] == 1) {
                $toemail = $variableEmail['emailDev'];
                $ccemail = null;
            } else {
                $toemail = $emailAuditor;
                $ccemail = $variableEmail['emailSubmitter'];
            }
            // Mail Structure
            $mailStructure = new UpdateExpired($periodInfo, $checklistDetail, $variableEmail['emailSubmitter']);
            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            //Audit Log
            $this->auditLogsShort('Update Expired Period Checklist ID:' . $id);
            //Log Period
            $this->storeLogPeriod($id, 9, 'Extend Period To ' . $request->end_date);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Update Expired Period Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Update Expired Period Checklist!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            MstPeriodeChecklists::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Period Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Period Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            $name = MstPeriodeChecklists::where('id', $id)->first();
            return redirect()->back()->with(['fail' => 'Failed to Delete Period Checklist ' . $name->period . '!']);
        }
    }
}

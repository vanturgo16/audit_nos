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
use App\Models\MstChecklists;
use App\Models\MstEmployees;
use App\Models\PeriodDealerAssesor;

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
        $today = now()->startOfDay();

        $request->validate([
            'period'     => 'required',
            'id_branch'  => 'required',
            'start_date' => ['required', 'date', 'after_or_equal:' . $today->toDateString()],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ], [
            'start_date.after_or_equal' => 'Failed, You cannot fill start date less than today',
            'end_date.after'            => 'Failed, End date must be greater than start date',
        ]);

        // Get period name id
        $mstPeriodNameId = MstPeriodName::where('period_name', $request->period)->value('id');

        // Get mapping assessor
        $mappingPDA = PeriodDealerAssesor::where('mst_period_name_id', $mstPeriodNameId)
            ->where('mst_dealers_id', $request->id_branch)
            ->first();

        if (!$mappingPDA || empty(json_decode($mappingPDA->assesor_ids, true))) {
            $dealerName = MstJaringan::where('id', $request->id_branch)->value('dealer_name');
            return back()->withInput()->with('fail',
                "No assessor has been assigned for dealer {$dealerName} in period {$request->period}. 
                Please assign the assessor first."
            );
        }

        DB::beginTransaction();
        try {
            // Create period
            $period = MstPeriodeChecklists::create([
                'period'      => $request->period,
                'id_branch'   => $request->id_branch,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'created_by'  => auth()->user()->email,
                'is_active'   => 1,
                'status'      => 0
            ]);

            // Update statuses
            $mappingPDA->update(['status' => 1]);
            MstPeriodName::where('id', $mstPeriodNameId)
                ->where('status', 0)
                ->update(['status' => 1]);

            // Get type jaringan
            $type = MstJaringan::where('id', $request->id_branch)->value('type');
            // Get checklist IDs only
            $checklistIds = MstMapChecklists::where('type_jaringan', $type)
                ->pluck('id_mst_checklist')
                ->filter();
            // Validate checklist exists in ONE query
            $validChecklistIds = MstChecklists::whereIn('id', $checklistIds)->pluck('id');
            // Prepare bulk insert
            $assignData = $validChecklistIds->map(function ($id) use ($period) {
                return [
                    'id_periode_checklist' => $period->id,
                    'id_mst_checklist'     => $id,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            })->toArray();

            MstAssignChecklists::insert($assignData);
            
            // // Small Data For Testing 
            // $testShort = ['47','49','52','353','354','366'];
            // foreach ($testShort as $item) {
            //     MstAssignChecklists::create([
            //         'id_periode_checklist' => $period->id,
            //         'id_mst_checklist' => $item
            //     ]);
            // }

            // Log
            $this->storeLogPeriod($period->id, 0, 'Initiate');
            $this->auditLogsShort('Create New Period Checklist ID: ' . $period->id);

            DB::commit();
            return back()->with('success', 'Success Create New Period Checklist');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('fail', 'Failed to Create New Period Checklist!');
        }
    }

    public function update(Request $request, $id)
    {
        $id    = decrypt($id);
        $today = now()->startOfDay();

        $request->validate([
            'period'     => 'required',
            'id_branch'  => 'required',
            'start_date' => ['required', 'date', 'after_or_equal:' . $today->toDateString()],
            'end_date'   => ['required', 'date', 'after:start_date'],
        ], [
            'start_date.after_or_equal' => 'Failed, You cannot fill start date less than today',
            'end_date.after'            => 'Failed, End date must be greater than start date',
        ]);

        $period = MstPeriodeChecklists::findOrFail($id);

        // Detect changes
        $period->fill([
            'period'     => $request->period,
            'id_branch'  => $request->id_branch,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);
        if (!$period->isDirty()) {
            return back()->with('info', 'Nothing Change, The data entered is the same as the previous one!');
        }

        DB::beginTransaction();
        try {
            $oldBranch = $period->getOriginal('id_branch');
            $newBranch = $request->id_branch;

            $oldPeriodName = $period->getOriginal('period');
            $newPeriodName = $request->period;

            // Handle Period Status Change
            if ($oldPeriodName != $newPeriodName) {
                $oldPeriodNameId = MstPeriodName::where('period_name', $oldPeriodName)->value('id');
                $remainingOld = MstPeriodeChecklists::where('period', $oldPeriodName)
                    ->where('id', '!=', $id)
                    ->count();
                if ($remainingOld == 0) {
                    MstPeriodName::where('id', $oldPeriodNameId)
                        ->update(['status' => 0]);
                }
                $newPeriodNameId = MstPeriodName::where('period_name', $newPeriodName)->value('id');
                MstPeriodName::where('id', $newPeriodNameId)
                    ->where('status', 0)
                    ->update(['status' => 1]);
            }

            // If branch changed → regenerate checklist
            if ($oldBranch != $newBranch) {
                // Check mapping assesor in new branch
                $mstPeriodNameId = MstPeriodName::where('period_name', $request->period)->value('id');
                $mappingPDA = PeriodDealerAssesor::where('mst_period_name_id', $mstPeriodNameId)
                    ->where('mst_dealers_id', $newBranch)
                    ->first();
                $assesorIds = $mappingPDA ? json_decode($mappingPDA->assesor_ids, true) : [];
                if (!$mappingPDA || empty($assesorIds)) {
                    $dealerName = MstJaringan::where('id', $newBranch)->value('dealer_name');
                    DB::rollBack();
                    return back()->withInput()->with('fail',
                        "Failed to change dealer to {$dealerName}. 
                        No assessor has been assigned for this dealer in period {$request->period}. 
                        Please assign the assessor first."
                    );
                }

                // Reset old mapping assessor status 
                PeriodDealerAssesor::where('mst_period_name_id', $mstPeriodNameId)
                    ->where('mst_dealers_id', $oldBranch)
                    ->update(['status' => 0]);
                // Activate new mapping assessor status 
                $mappingPDA->update(['status' => 1]);

                // Delete old assign
                MstAssignChecklists::where('id_periode_checklist', $id)->delete();
                // Get type jaringan
                $type = MstJaringan::where('id', $newBranch)->value('type');
                // Get mapped checklist ids
                $checklistIds = MstMapChecklists::where('type_jaringan', $type)
                    ->pluck('id_mst_checklist')
                    ->filter();
                // Validate in one query
                $validChecklistIds = MstChecklists::whereIn('id', $checklistIds)->pluck('id');
                // Bulk insert
                $assignData = $validChecklistIds->map(function ($checklistId) use ($id) {
                    return [
                        'id_periode_checklist' => $id,
                        'id_mst_checklist'     => $checklistId,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ];
                })->toArray();

                MstAssignChecklists::insert($assignData);
            }

            // Save period
            $period->save();

            // Audit Log
            $this->auditLogsShort('Update Period Checklist ID: ' . $id);

            DB::commit();
            return back()->with('success', 'Success Update Period Checklist');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('fail', 'Failed to Update Period Checklist!');
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
            $period     = MstPeriodeChecklists::findOrFail($id);
            $periodName = $period->period;
            $branchId   = $period->id_branch;

            // Count remaining period with same name
            $remainingCount = MstPeriodeChecklists::where('period', $periodName)->count();
            // Get mst_period_name_id
            $mstPeriodNameId = MstPeriodName::where('period_name', $periodName)->value('id');
            // Reset mapping PDA status (if exists)
            PeriodDealerAssesor::where('mst_period_name_id', $mstPeriodNameId)
                ->where('mst_dealers_id', $branchId)
                ->update(['status' => 0]);
            // If this is the last period → reset master period status
            if ($remainingCount == 1) {
                MstPeriodName::where('id', $mstPeriodNameId)
                    ->update(['status' => 0]);
            }

            // Delete period
            $period->delete();

            // Audit log
            $this->auditLogsShort('Delete Period Checklist ID: ' . $id);

            DB::commit();
            return back()->with('success', 'Success Delete Period Checklist');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('fail',
                "Failed to delete period checklist for period {$periodName}."
            );
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ChecklistJaringan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\MailingTrait;

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
use App\Models\MstEmployees;

class MstAssignChecklistController extends Controller
{
    use AuditLogsTrait;
    use MailingTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $period = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)->first();

        $assign = MstAssignChecklists::where('id_periode_checklist', $id)->first();
        $check = ($assign == null) ? 0 : 1;

        $typeChecks = MstDropdowns::select('name_value')->where('category', 'Type Checklist')->get();
        foreach ($typeChecks as $item) {
            $countParent = MstAssignChecklists::leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', '=', 'mst_checklists.id')
                ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
                ->where('mst_assign_checklists.id_periode_checklist', $id)
                ->where('mst_parent_checklists.type_checklist', $item->name_value)
                ->distinct('mst_parent_checklists.id')->count('mst_parent_checklists.id');
            $countCheck = MstAssignChecklists::leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', '=', 'mst_checklists.id')
                ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
                ->where('mst_assign_checklists.id_periode_checklist', $id)
                ->where('mst_parent_checklists.type_checklist', $item->name_value)->count();
            $item->countParent = $countParent;
            $item->countCheck = $countCheck;
        }

        if ($request->ajax()) {
            return DataTables::of($typeChecks)
                ->addColumn('action', function ($data) use ($period) {
                    return view('assignchecklist.action', compact('data', 'period'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Assign Checklist (' . $period->period . ')');

        return view('assignchecklist.index', compact('period', 'check'));
    }

    public function type(Request $request, $id, $type)
    {
        $id = decrypt($id);
        $period = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)->first();

        $checklists = MstChecklists::select('mst_checklists.id as id_checklist', 'mst_checklists.*', 'mst_parent_checklists.*')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_parent_checklists.type_checklist', $type)
            ->orderby('mst_parent_checklists.order_no')
            ->orderby('mst_checklists.order_no')
            ->get();

        // IF Still Initiate Use Data From Master
        if ($period->status == 0) {
            $assignChecks = MstAssignChecklists::select(
                'mst_assign_checklists.id as idAssCheck',
                'mst_parent_checklists.parent_point_checklist',
                'mst_checklists.*',
                'mst_parent_checklists.order_no as orderParent',
                'mst_checklists.order_no as orderCheck'
            )
                ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', '=', 'mst_checklists.id')
                ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
                ->where('mst_assign_checklists.id_periode_checklist', $id)
                ->where('mst_parent_checklists.type_checklist', $type)
                ->orderBy('orderParent') // Order by orderParent
                ->orderBy('orderCheck')  // Then order by orderCheck
                ->get();
        } else {
            $assignChecks = MstAssignChecklists::select('mst_assign_checklists.id as idAssCheck', 'mst_assign_checklists.*')
                ->where('id_periode_checklist', $id)->where('type_checklist', $type)
                ->orderBy('order_no_parent') // Order by orderParent
                ->orderBy('order_no_checklist')  // Then order by orderCheck
                ->get();
        }

        if ($request->ajax()) {
            $data = DataTables::of($assignChecks)
                ->addColumn('action', function ($data) use ($period, $checklists) {
                    return view('assignchecklist.type.action', compact('data', 'period', 'checklists'));
                })
                ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Assign Checklist (' . $period->period . ') type: ' . $type);

        return view('assignchecklist.type.index', compact('period', 'checklists', 'type'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);

        $request->validate([
            'id_mst_checklist' => 'required',
        ]);

        //Check
        if (MstAssignChecklists::where('id_periode_checklist', $id)->where('id_mst_checklist', $request->id_mst_checklist)->exists()) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Choose Different Checklist']);
        }

        DB::beginTransaction();
        try {
            MstAssignChecklists::create(['id_periode_checklist' => $id, 'id_mst_checklist' => $request->id_mst_checklist]);

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

        DB::beginTransaction();
        try {
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

        // Check All Checklist Has Mark Or Not Yet First
        $assignChecks = MstAssignChecklists::select('mst_assign_checklists.id', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.sub_point_checklist')
            ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', 'mst_checklists.id')
            ->where('mst_assign_checklists.id_periode_checklist', $id)->get();
        foreach ($assignChecks as $item) {
            if (!MstChecklistDetails::where('id_checklist', $item->id_mst_checklist)->exists()) {
                return redirect()->back()->with(['fail' => 'Failed, Checklist = "' . $item->sub_point_checklist . '" Dont Have Any Mark Yet!, Please Update The Checklist']);
            }
        }

        // MAILING
        // [ INITIATE VARIABLE ] 
        // Group By Type Checklist For Create Paper Assign (Checklist Jaringan)
        $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
        $groupTypeChecks = MstAssignChecklists::select('mst_parent_checklists.type_checklist', DB::raw('COUNT(mst_assign_checklists.id) as countCheck'))
            ->leftJoin('mst_checklists', 'mst_assign_checklists.id_mst_checklist', '=', 'mst_checklists.id')
            ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->where('mst_assign_checklists.id_periode_checklist', $id)
            ->groupBy('mst_parent_checklists.type_checklist')
            ->orderByRaw("FIELD(mst_parent_checklists.type_checklist, '" . $sortOrder->implode("','") . "')")
            ->get();
        // Email Variable
        $variableEmail = $this->variableEmail();
        $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type', DB::raw('(SELECT COUNT(*) FROM mst_assign_checklists WHERE mst_assign_checklists.id_periode_checklist = mst_periode_checklists.id) as totalChecklist'))
            ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
        $emailAuditor = MstEmployees::leftjoin('users', 'users.email', 'mst_employees.email')->where('mst_employees.id_dealer', $periodInfo->id_branch)->where('users.role', 'Internal Auditor Dealer')->pluck('users.email');
        if ($emailAuditor->isEmpty()) {
            return redirect()->back()->with(['fail' => 'Failed, Data Employee Internal Auditor Jaringan "' . $periodInfo->dealer_name . '" Not Exist']);
        }
        // Recepient Email
        if ($variableEmail['devRule'] == 1) {
            $toemail = $ccemail = $variableEmail['emailDev'];
            $ccemail = null;
        } else {
            $toemail = $emailAuditor;
            $ccemail = $variableEmail['emailSubmitter'];
        }
        // Mail Structure
        $mailStructure = new SubmitAssignChecklist($periodInfo, $groupTypeChecks, $variableEmail['emailSubmitter']);

        DB::beginTransaction();
        try {
            // Update Assign Checklist
            foreach ($assignChecks as $item) {
                // Find Detail Checklist
                $detailMstCheck = MstChecklists::select(
                    'mst_parent_checklists.type_checklist',
                    'mst_parent_checklists.parent_point_checklist',
                    'mst_checklists.*',
                    'mst_parent_checklists.order_no as orderParent',
                    'mst_checklists.order_no as orderCheck',
                )
                    ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                    ->where('mst_checklists.id', $item->id_mst_checklist)
                    ->first();
                $mark = MstChecklistDetails::select('meta_name')->where('id_checklist', $item->id_mst_checklist)->get()->toArray();
                $mark = empty($mark) ? null : $mark;
                // Update Assign
                if ($detailMstCheck) {
                    MstAssignChecklists::where('id', $item->id)->update([
                        'order_no_parent' => $detailMstCheck->orderParent,
                        'order_no_checklist' => $detailMstCheck->orderCheck,
                        'type_checklist' => $detailMstCheck->type_checklist,
                        'parent_point_checklist' => $detailMstCheck->parent_point_checklist,
                        'path_guide_parent' => $detailMstCheck->path_guide_premises,
                        'child_point_checklist' => $detailMstCheck->child_point_checklist,
                        'sub_point_checklist' => $detailMstCheck->sub_point_checklist,
                        'indikator' => $detailMstCheck->indikator,
                        'ms' => $detailMstCheck->mandatory_silver,
                        'mg' => $detailMstCheck->mandatory_gold,
                        'mp' => $detailMstCheck->mandatory_platinum,
                        'upload_file' => $detailMstCheck->upload_file,
                        'path_guide_checklist' => $detailMstCheck->path_guide_checklist,
                        'mark' => json_encode($mark)
                    ]);
                }
            }

            // Create Paper Checklist
            foreach ($groupTypeChecks as $item) {
                ChecklistJaringan::create([
                    'id_periode' => $id, 'type_checklist' => $item->type_checklist,
                    'total_checklist' => $item->countCheck, 'checklist_remaining' => $item->countCheck,
                ]);
            }

            // Update Period Status To Assigned
            MstPeriodeChecklists::where('id', $id)->update(['status' => '1']);

            // Send Email
            Mail::to($toemail)->cc($ccemail)->send($mailStructure);

            //Log Period
            $this->storeLogPeriod($id, 1, 'Assign To Internal Auditor');

            //Audit Log
            $this->auditLogsShort('Assign New Period Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Submit Assign Checklist To Internal Auditor, Email Sent']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Submit Assign Checklist!']);
        }
    }
}

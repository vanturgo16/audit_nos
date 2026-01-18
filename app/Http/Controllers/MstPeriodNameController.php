<?php

namespace App\Http\Controllers;

use App\Models\MstJaringan;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstPeriodName;
use App\Models\PeriodDealerAssesor;
use App\Models\User;

class MstPeriodNameController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstPeriodName::orderby('created_at', 'desc')->get();

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

        return view('periodname.index', compact('datas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'period_name' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $check = MstPeriodName::where('period_name', $request->period_name)->first();
        if ($check != null) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Period Name Is Exist']);
        }

        DB::beginTransaction();
        try {
            $periodName = MstPeriodName::create([
                'period_name' => $request->period_name
            ]);

            $dealers = MstJaringan::get();

            foreach ($dealers as $item) {
                PeriodDealerAssesor::create([
                    'mst_period_name_id' => $periodName->id,
                    'mst_dealers_id' => $item->id,
                    'status' => 0,
                ]);
            }

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
        $validate = Validator::make($request->all(), [
            'period_name' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $databefore = MstPeriodName::where('id', $id)->first();
        $check = MstPeriodName::where('period_name', '!=', $databefore->period_name)->where('period_name', $request->period_name)->first();
        if ($check != null) {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Period Name Is Exist']);
        }
        $databefore->period_name = $request->period_name;

        if ($databefore->isDirty()) {
            DB::beginTransaction();
            try {
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

    public function indexAssesorAssign(Request $request, $id)
    {
        $id = decrypt($id);

        $periodName = optional(MstPeriodName::where('id', $id)->first())->period_name;

        if ($request->ajax()) {

            $datas = PeriodDealerAssesor::select([
                    'period_dealer_assessors.id',
                    'mst_period_name.period_name',
                    'mst_dealers.type',
                    'mst_dealers.dealer_name',
                    'period_dealer_assessors.status',
                    'period_dealer_assessors.assesor_ids',
                    'users.email as last_updated_by'
                ])
                ->leftJoin('mst_period_name', 'period_dealer_assessors.mst_period_name_id', '=', 'mst_period_name.id')
                ->leftJoin('mst_dealers', 'period_dealer_assessors.mst_dealers_id', '=', 'mst_dealers.id')
                ->leftJoin('users', 'period_dealer_assessors.updated_by', '=', 'users.id')
                ->where('period_dealer_assessors.mst_period_name_id', $id)
                ->orderBy('mst_dealers.dealer_name')
                ->get()
                ->map(function($row) {
                    // convert JSON IDs to emails
                    $userIds = json_decode($row->assesor_ids, true) ?: [];
                    $row->assessors = User::whereIn('id', $userIds)
                                        ->pluck('email')
                                        ->toArray();
                    return $row;
                });

            $listAssesor = User::select('id', 'email')->where('role', 'Assessor Main Dealer')->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($listAssesor) {
                    return view('periodname.assesor_assign.action', compact('data', 'listAssesor'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Assesor Assign, Period Name ID = '. $id);

        return view('periodname.assesor_assign.index', compact('id', 'periodName'));
    }

    public function updateAssesorAssign(Request $request, $id)
    {
        $id = decrypt($id);

        // Validate input
        $request->validate([
            'assessors.*' => 'exists:users,id'
        ]);

        $periodDealer = PeriodDealerAssesor::findOrFail($id);

        // Decode existing assessor IDs
        $existingAssessorIds = json_decode($periodDealer->assesor_ids, true) ?: [];

        // Sort both arrays to compare easily
        $existingAssessorIdsSorted = collect($existingAssessorIds)->sort()->values()->all();
        $newAssessorIdsSorted = collect($request->assessors)
            ->filter(function($v) {
                return $v !== null && $v !== ''; // remove empty strings
            })
            ->map(function($v) {
                return (int) $v; // cast to int
            })
            ->sort()->values()->all();

        // Check if any changes
        if ($existingAssessorIdsSorted === $newAssessorIdsSorted) {
            return redirect()->back()->with([
                'info' => 'Nothing Changed. The data entered is the same as the previous one!'
            ]);
        }

        // Check if status allows update
        if ($periodDealer->status === 1) {
            return redirect()->back()->with([
                'error' => 'Cannot update. The period has already been starting!'
            ]);
        }

        // Wrap in DB transaction
        DB::transaction(function () use ($periodDealer, $newAssessorIdsSorted, $id) {
            $periodDealer->assesor_ids = json_encode($newAssessorIdsSorted);
            $periodDealer->updated_by = auth()->id();
            $periodDealer->save();

            // Audit log
            $this->auditLogsShort('Update Assesor Assign Period Dealer Assesor ID (' . $id . ')');
        });

        return redirect()->back()->with([
            'success' => 'Assessors updated successfully!'
        ]);
    }
}

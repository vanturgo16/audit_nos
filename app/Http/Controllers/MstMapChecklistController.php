<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

// Model
use App\Models\MstDropdowns;
use App\Models\MstMapChecklists;
use App\Models\MstParentChecklists;
use App\Models\MstChecklists;

use function Spatie\Ignition\ErrorPage\report;

class MstMapChecklistController extends Controller
{
    use AuditLogsTrait;

    // INDEX: List all Type Jaringan
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = MstDropdowns::where('category', 'Type Dealer')->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('mapchecklist.action', compact('data'));
                })
                ->toJson();
        }

        $this->auditLogsShort('View List Type Jaringan Mst MapChecklist');
        return view('mapchecklist.index');
    }

    // INDEX: List Type Checklist for a given Type Jaringan
    public function type(Request $request, $type)
    {
        $type = decrypt($type);
        $sortOrderQuery = MstDropdowns::select('id', 'name_value', 'created_at')->where('category', 'Type Checklist');
        $dataTypeMaps = MstMapChecklists::selectRaw("
                mst_dropdowns.id AS idUnique,
                mst_parent_checklists.type_checklist,
                COUNT(DISTINCT mst_mapchecklists.id_parent_checklist) AS total_parent,
                COUNT(mst_mapchecklists.id) AS total_checklist
            ")
            ->leftJoin('mst_parent_checklists', 'mst_mapchecklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->leftJoinSub($sortOrderQuery, 'mst_dropdowns', function ($join) {
                $join->on('mst_dropdowns.name_value', '=', 'mst_parent_checklists.type_checklist');
            })
            ->where('mst_mapchecklists.type_jaringan', $type)
            ->groupBy('mst_parent_checklists.type_checklist', 'mst_dropdowns.id', 'mst_dropdowns.created_at')
            ->orderBy('mst_dropdowns.created_at')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($dataTypeMaps)
                ->addColumn('action', function ($data) use ($type) {
                    return view('mapchecklist.typechecklist.action', compact('data', 'type'));
                })
                ->toJson();
        }

        $existingTypes = $dataTypeMaps->pluck('type_checklist');
        $mstTypeChecks = MstDropdowns::where('category', 'Type Checklist')
            ->whereNotIn('name_value', $existingTypes)
            ->orderBy('created_at')
            ->get();

        $this->auditLogsShort('View List Type Checklist Mst Mapping Checklist');
        return view('mapchecklist.typechecklist.index', compact('mstTypeChecks', 'type'));
    }

    // ADD Type Checklist to Type Jaringan
    public function addtype($type, Request $request)
    {
        $type       = decrypt($type);
        $typeChecks = $request->type_checklist;

        DB::beginTransaction();
        try {
            $parentIds  = MstParentChecklists::whereIn('type_checklist', $typeChecks)->pluck('id');
            $checklists = MstChecklists::whereIn('id_parent_checklist', $parentIds)->get();
            $existing = MstMapChecklists::where('type_jaringan', $type)
                ->whereIn('id_parent_checklist', $parentIds)
                ->pluck('id_parent_checklist', 'id_mst_checklist')
                ->toArray();

            $addedCount = 0;
            foreach ($checklists as $row) {
                if (isset($existing[$row->id]) && $existing[$row->id] == $row->id_parent_checklist) {
                    continue;
                }
                MstMapChecklists::create([
                    'type_jaringan'       => $type,
                    'id_parent_checklist' => $row->id_parent_checklist,
                    'id_mst_checklist'    => $row->id,
                ]);
                $addedCount++;
            }

            $this->auditLogsShort("Added Type Checklist ({$addedCount} added) to Type Jaringan: ".$type);
            DB::commit();

            return back()->with('success', "Successfully added {$addedCount} Type Checklist(s) to Type Jaringan: ".$type);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('fail', 'Failed to add Type Checklist!');
        }
    }

    // DELETE Type Checklist from Type Jaringan
    public function deletetype(Request $request, $type)
    {
        $typeChecklist = decrypt($type);
        $typeJaringan  = $request->typeJaringan;

        DB::beginTransaction();
        try {
            $parentIds = MstParentChecklists::where('type_checklist', $typeChecklist)->pluck('id');
            $deleteCount = MstMapChecklists::where('type_jaringan', $typeJaringan)
                ->whereIn('id_parent_checklist', $parentIds)
                ->count();

            MstMapChecklists::where('type_jaringan', $typeJaringan)
                ->whereIn('id_parent_checklist', $parentIds)
                ->delete();

            $infoString = "Type Jaringan: ".$typeJaringan." | Type Checklist: ".$typeChecklist." | Deleted: ".$deleteCount;
            $this->auditLogsShort("Deleted Type Checklist Mapping (".$infoString.")");

            DB::commit();
            return back()->with('success', "Successfully deleted Type Checklist (".$infoString.")");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('fail', "Failed to delete Type Checklist: ".$typeChecklist);
        }
    }

    // DETAIL: Manage Checklists inside Type Checklist
    public function detail(Request $request, $type, $typecheck)
    {
        $type      = decrypt($type);
        $typecheck = decrypt($typecheck);

        if ($request->ajax()) {
            $mappedChecklists = MstMapChecklists::join('mst_checklists', 'mst_checklists.id', '=', 'mst_mapchecklists.id_mst_checklist')
                ->join('mst_parent_checklists', 'mst_parent_checklists.id', '=', 'mst_checklists.id_parent_checklist')
                ->where('mst_mapchecklists.type_jaringan', $type)
                ->where('mst_parent_checklists.type_checklist', $typecheck)
                ->orderBy('mst_parent_checklists.order_no')
                ->orderBy('mst_checklists.order_no')
                ->get([
                    'mst_mapchecklists.id',
                    'mst_parent_checklists.parent_point_checklist',
                    'mst_checklists.child_point_checklist',
                    'mst_checklists.sub_point_checklist',
                    'mst_checklists.indikator',
                ]);
                
            return DataTables::of($mappedChecklists)
                ->addColumn('action', function ($data) {
                    return view('mapchecklist.typechecklist.manage.action', compact('data'));
                })
                ->rawColumns(['indikator', 'action'])
                ->toJson();
        }

        $availableChecklists = MstChecklists::join('mst_parent_checklists', 'mst_parent_checklists.id', '=', 'mst_checklists.id_parent_checklist')
            ->where('mst_parent_checklists.type_checklist', $typecheck)
            ->whereNotExists(function ($query) use ($type) {
                $query->select(DB::raw(1))
                    ->from('mst_mapchecklists')
                    ->whereColumn('mst_mapchecklists.id_mst_checklist', 'mst_checklists.id')
                    ->where('mst_mapchecklists.type_jaringan', $type);
            })
            ->orderBy('mst_parent_checklists.order_no')
            ->orderBy('mst_checklists.order_no')
            ->get([
                'mst_checklists.id',
                'mst_parent_checklists.id as id_parent_checklist',
                'mst_parent_checklists.type_checklist',
                'mst_parent_checklists.parent_point_checklist',
                'mst_checklists.child_point_checklist',
                'mst_checklists.sub_point_checklist',
                'mst_checklists.indikator',
            ]);

        $this->auditLogsShort("View Manage Mapping Checklist for Type Jaringan: ".$type.", Type Checklist: ".$typecheck);
        return view('mapchecklist.typechecklist.manage.index', compact('type', 'typecheck', 'availableChecklists'));
    }

    // ADD a Checklist inside Type Checklist
    public function addChecklist(Request $request, $type)
    {
        $type   = decrypt($type);
        $parent = MstParentChecklists::findOrFail($request->id_parent_checklist);
        $check  = MstChecklists::findOrFail($request->id_mst_checklist);

        DB::beginTransaction();
        try {
            $infoParts = array_filter([
                $type,
                $parent->type_checklist,
                $parent->parent_point_checklist,
                $check->child_point_checklist,
                $check->sub_point_checklist
            ]);

            $infoString = implode(' -> ', $infoParts);

            $store = MstMapChecklists::create([
                'type_jaringan'       => $type,
                'id_parent_checklist' => $request->id_parent_checklist,
                'id_mst_checklist'    => $request->id_mst_checklist,
            ]);

            $this->auditLogsShort("Added mapping checklist ID: ".$store->id." (".$infoString.")");
            DB::commit();

            return back()->with('success', "Successfully added mapping checklist: ".$infoString);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('fail', 'Failed to add mapping checklist!');
        }
    }

    // DELETE a Checklist inside Type Checklist
    public function deleteChecklist($id)
    {
        $id   = decrypt($id);
        $data = MstMapChecklists::findOrFail($id);

        $parent = MstParentChecklists::find($data->id_parent_checklist);
        $check  = MstChecklists::find($data->id_mst_checklist);

        DB::beginTransaction();
        try {
            $infoParts = array();
            if ($data->type_jaringan) $infoParts[] = $data->type_jaringan;
            if ($parent) $infoParts[] = $parent->type_checklist;
            if ($parent) $infoParts[] = $parent->parent_point_checklist;
            if ($check) $infoParts[] = $check->child_point_checklist;
            if ($check) $infoParts[] = $check->sub_point_checklist;

            $infoString = implode(' -> ', $infoParts);

            $data->delete();
            $this->auditLogsShort("Deleted mapping checklist ID: ".$id." (".$infoString.")");

            DB::commit();
            return back()->with('success', "Successfully removed mapping checklist: ".$infoString);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('fail', 'Failed to remove mapping checklist!');
        }
    }
}

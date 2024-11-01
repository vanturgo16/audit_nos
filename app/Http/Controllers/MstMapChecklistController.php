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

class MstMapChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstDropdowns::where('category', 'Type Dealer')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('mapchecklist.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Type Jaringan Mst MapChecklist');

        return view('mapchecklist.index', compact('datas'));
    }

    public function type(Request $request, $type)
    {
        $type = decrypt($type);

        $sortOrder = MstDropdowns::where('category', 'Type Checklist')->orderBy('created_at')->pluck('name_value');
        $dataTypeMaps = MstMapChecklists::select('mst_parent_checklists.type_checklist', DB::raw('COUNT(mst_mapchecklists.id) as countMap'))
            ->leftjoin('mst_parent_checklists', 'mst_mapchecklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_mapchecklists.type_jaringan', $type)
            ->groupBy('mst_parent_checklists.type_checklist')
            ->orderByRaw("FIELD(mst_parent_checklists.type_checklist, '" . $sortOrder->implode("','") . "')")
            ->get();
        $dataTypeMaps->each(function ($item, $index) {
            $item->idUnique = $index + 1;
        });
        $typeCheckExisting = $dataTypeMaps->pluck('type_checklist');

        $mstTypeChecks = MstDropdowns::select('name_value')
            ->where('category', 'Type Checklist')
            ->whereNotIn('name_value', $typeCheckExisting)
            ->orderBy('created_at')->get();

        if ($request->ajax()) {
            return DataTables::of($dataTypeMaps)
                ->addColumn('action', function ($data) use ($type) {
                    return view('mapchecklist.typechecklist.action', compact('data', 'type'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist Mst Mapping Checklist');

        return view('mapchecklist.typechecklist.index', compact('mstTypeChecks', 'type'));
    }

    public function addtype($type, Request $request)
    {
        $type = decrypt($type); // type jaringan
        $typeChecks = $request->type_checklist; // selected type checklist

        DB::beginTransaction();
        try {
            foreach ($typeChecks as $item) {
                $mstParents = MstParentChecklists::select('id')->where('type_checklist', $item)->get();
                foreach ($mstParents as $parent) {
                    $check = MstMapChecklists::where('id_parent_checklist', $parent->id)->where('type_jaringan', $type)->first();
                    if ($check == null) {
                        MstMapChecklists::create(['id_parent_checklist' => $parent->id, 'type_jaringan' => $type]);
                    }
                }
            }

            //Audit Log
            $this->auditLogsShort('Add Type Checklist Mst MapChecklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Add New Type Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Add New Type Checklist!']);
        }
    }

    public function deletetype(Request $request, $type)
    {
        $type = decrypt($type);
        $idParent = MstParentChecklists::where('type_checklist', $type)->pluck('id');

        DB::beginTransaction();
        try {
            MstMapChecklists::where('type_jaringan', $request->typeJaringan)->whereIn('id_parent_checklist', $idParent)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Type Checklist Mst MapChecklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Type Checklist : ' . $type]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Type Checklist! : ' . $type]);
        }
    }

    public function detail(Request $request, $type, $typecheck)
    {
        $type = decrypt($type); // type jaringan
        $typecheck = decrypt($typecheck); // type checklist

        $dataMaps = MstMapChecklists::select('mst_mapchecklists.id as idMap', 'mst_parent_checklists.id as idParent', 'mst_parent_checklists.parent_point_checklist')
            ->leftjoin('mst_parent_checklists', 'mst_mapchecklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('mst_mapchecklists.type_jaringan', $type)
            ->where('mst_parent_checklists.type_checklist', $typecheck)
            ->orderBy('mst_parent_checklists.order_no', 'asc');
        $idParent = $dataMaps->pluck('idParent');
        $dataMaps = $dataMaps->get();

        $mstParents = MstParentChecklists::select('id', 'parent_point_checklist')
            ->where('type_checklist', $typecheck)
            ->whereNotIn('id', $idParent)
            ->orderBy('order_no', 'asc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($dataMaps)
                ->addColumn('action', function ($data) {
                    return view('mapchecklist.typechecklist.parentcheck.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Parent Type Checklist Mst MapChecklist');

        return view('mapchecklist.typechecklist.parentcheck.index', compact('mstParents', 'type', 'typecheck'));
    }

    public function addparent(Request $request, $type)
    {
        $type = decrypt($type); // type jaringan
        $idParents = $request->id_parent; // selected id parent

        DB::beginTransaction();
        try {
            foreach ($idParents as $item) {
                MstMapChecklists::create([
                    'id_parent_checklist' => $item,
                    'type_jaringan' => $type
                ]);
            }

            //Audit Log
            $this->auditLogsShort('Add Parent Checklist Mst MapChecklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Add Parent Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Add Parent Checklist!']);
        }
    }

    public function deleteparent($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            MstMapChecklists::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Parent Mst MapChecklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Parent Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Parent Checklist!']);
        }
    }
}

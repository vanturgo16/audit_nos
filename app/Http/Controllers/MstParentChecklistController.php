<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

// Trait
use App\Traits\AuditLogsTrait;
use App\Traits\OrderTrait;

// Model
use App\Models\MstDropdowns;
use App\Models\MstParentChecklists;
use App\Models\MstRules;


class MstParentChecklistController extends Controller
{
    use AuditLogsTrait, OrderTrait;

    // INDEX GROUP BY TYPE CHECKLIST
    public function typechecklist(Request $request)
    {
        if ($request->ajax()) {
            $datas = MstDropdowns::select(
                    'mst_dropdowns.name_value',
                    DB::raw('COUNT(mst_parent_checklists.id) as amount')
                )
                ->leftJoin('mst_parent_checklists', 'mst_parent_checklists.type_checklist', '=', 'mst_dropdowns.name_value')
                ->where('mst_dropdowns.category', 'Type Checklist')
                ->groupBy('mst_dropdowns.name_value')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('parentchecklist.type.action', compact('data'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist in Parent');

        return view('parentchecklist.type.index');
    }

    // INDEX PER TYPE
    public function index(Request $request, $type)
    {
        if ($request->ajax()) {
            $datas = MstParentChecklists::where('type_checklist', $type)
                ->orderBy('type_checklist', 'asc')
                ->orderBy('order_no', 'asc')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('parentchecklist.action', compact('data'));
                })
                ->toJson();
        }

        $typePerCheck   = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $useGuideParent = !in_array($type, $typePerCheck);

        //Audit Log
        $this->auditLogsShort('View List Mst Parent Checklist');

        return view('parentchecklist.index', compact('type', 'useGuideParent'));
    }

    // DETAIL PARENT
    public function detail($id)
    {
        $id             = decrypt($id);
        $parent         = MstParentChecklists::where('id', $id)->first();
        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $orders         = MstParentChecklists::where('type_checklist', $parent->type_checklist)->orderBy('order_no', 'asc')->get();
        $typePerCheck   = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();

        //Audit Log
        $this->auditLogsShort('View Detail Parent Checklist ID (' . $id . ')');

        return view('parentchecklist.detail', compact('id', 'parent', 'type_checklist', 'orders', 'typePerCheck'));
    }

    // STORE NEW PARENT
    public function store(Request $request)
    {
        $typePerCheck    = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $isGuideRequired = !in_array($request->type_checklist, $typePerCheck);

        $request->validate([
            'type_checklist'         => 'required',
            'parent_point_checklist' => 'required',
            'guideParent'            => $isGuideRequired
                ? 'required|image|mimes:jpg,jpeg,png|max:10240'
                : 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $pathGuide = null;
            // Upload file if exists
            if ($request->hasFile('guideParent')) {
                $file      = $request->file('guideParent');
                $pathGuide = Storage::disk('s3')->putFileAs(
                    'guide/parent', $file, time() . '-1_' . $file->getClientOriginalName()
                );
            }

            // Lock rows to prevent race condition
            $lastOrderNo = MstParentChecklists::where('type_checklist', $request->type_checklist)
                ->lockForUpdate()
                ->max('order_no');
            $orderNo = $lastOrderNo ? $lastOrderNo + 1 : 1;

            // Store data
            $parentChecklist = MstParentChecklists::create([
                'order_no'               => $orderNo,
                'type_checklist'         => $request->type_checklist,
                'parent_point_checklist' => $request->parent_point_checklist,
                'path_guide_premises'    => $pathGuide,
            ]);

            // Audit log
            $this->auditLogsShort("Create New Parent Checklist ID: {$parentChecklist->id}");

            DB::commit();
            return back()->with('success', "Success Create New Parent Checklist {$request->parent_point_checklist}");
        } catch (\Throwable $e) {
            DB::rollBack();

            // Delete uploaded file if transaction fails
            if (!empty($pathGuide) && Storage::disk('s3')->exists($pathGuide)) {
                Storage::disk('s3')->delete($pathGuide);
            }
            \Log::error('Store Parent Checklist Error: ' . $e->getMessage());

            return back()->with('fail', 'Failed to Create New Parent Checklist!');
        }
    }

    // UPDATE SELECTED PARENT
    public function update(Request $request, $id)
    {
        $id           = decrypt($id);
        $newGuidePath = null;

        $request->validate([
            'type_checklist_current' => 'required',
            'order_current'          => 'required',
            'type_checklist'         => 'required',
            'parent_point_checklist' => 'required',
            'guide_parent'           => 'nullable|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        DB::beginTransaction();
        try {
            $parent       = MstParentChecklists::findOrFail($id);
            $oldGuidePath = $parent->path_guide_premises;

            // Upload Guide File
            if ($request->hasFile('guide_parent')) {
                $file = $request->file('guide_parent');
                $newGuidePath  = Storage::disk('s3')->putFileAs(
                    'guide/parent', $file, time() . '-1_' . $file->getClientOriginalName()
                );
                $pathGuide = $newGuidePath;
            } else {
                $pathGuide = $oldGuidePath;
            }

            // Detect changes
            $parent->fill([
                'order_no'               => $request->order_no,
                'type_checklist'         => $request->type_checklist,
                'parent_point_checklist' => $request->parent_point_checklist,
                'path_guide_premises'    => $pathGuide
            ]);

            // If no changes return
            if (!$parent->isDirty()) {
                return redirect()
                    ->route('parentchecklist.detail', encrypt($id))
                    ->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
            }

            $reqOrder = $request->order_no;
            if (!in_array($reqOrder, ['0', '99999'])) {
                // If switch with other parent, update order destination parent with current order
                $target = MstParentChecklists::where('type_checklist', $request->type_checklist)
                    ->where('order_no', $reqOrder)
                    ->first();
                if ($target) {
                    $target->update([
                        'order_no' => $request->order_current
                    ]);
                }
            }

            // Update parent
            $parent->update([
                'order_no'               => $reqOrder,
                'type_checklist'         => $request->type_checklist,
                'parent_point_checklist' => $request->parent_point_checklist,
                'path_guide_premises'    => $pathGuide
            ]);

            // Reindex order
            $this->reindexParentPoint($request->type_checklist);
            if ($request->type_checklist !== $request->type_checklist_current) {
                $this->reindexParentPoint($request->type_checklist_current);
            }

            // Delete old file (when uploaded new and success update)
            if ($request->hasFile('guide_parent') && $oldGuidePath) {
                if (Storage::disk('s3')->exists($oldGuidePath)) {
                    Storage::disk('s3')->delete($oldGuidePath);
                }
            }

            // Audit Log
            $this->auditLogsShort('Update Parent Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()
                ->route('parentchecklist.index', $request->type_checklist)
                ->with(['success' => 'Success Update Parent Checklist']);

        } catch (Exception $e) {
            DB::rollback();

            // Delete New Uploaded File if Error
            if ($newGuidePath && Storage::disk('s3')->exists($newGuidePath)) {
                Storage::disk('s3')->delete($newGuidePath);
            }

            \Log::error('Update Parent Checklist Error: ' . $e->getMessage());
            return redirect()->back()->with(['fail' => 'Failed to Update Parent Checklist!']);
        }
    }

    // AJAX FIND LIST ORDER NO BY TYPE CHECKLIST
    public function mappingOrderNo($type_checklist)
    {
        $orders = MstParentChecklists::where('type_checklist', $type_checklist)
            ->orderBy('order_no', 'asc')
            ->get();
        return response()->json($orders);
    }
}

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
use App\Models\MstChecklists;
use App\Models\MstChecklistDetails;
use App\Models\MstDropdowns;
use App\Models\MstParentChecklists;
use App\Models\MstRules;

class MstChecklistController extends Controller
{
    use AuditLogsTrait, OrderTrait;

    // INDEX GROUP BY TYPE CHECKLIST
    public function typechecklist(Request $request)
    {
        if ($request->ajax()) {
            $datas = MstDropdowns::select(
                    'mst_dropdowns.name_value',
                    DB::raw('COUNT(mst_checklists.id) as amount')
                )
                ->leftJoin('mst_parent_checklists', 'mst_parent_checklists.type_checklist', '=', 'mst_dropdowns.name_value')
                ->leftJoin('mst_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
                ->where('mst_dropdowns.category', 'Type Checklist')
                ->groupBy('mst_dropdowns.name_value')
                ->get();
                
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('checklist.type.action', compact('data'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist');

        return view('checklist.type.index');
    }

    // INDEX PER TYPE
    public function index(Request $request, $type)
    {
        if ($request->ajax()) {
            $datas = MstChecklists::join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
                ->select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist')
                ->where('mst_parent_checklists.type_checklist', $type)
                ->orderby('mst_parent_checklists.order_no')
                ->orderby('mst_checklists.order_no')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('checklist.action', compact('data'));
                })
                ->toJson();
        }

        $listParents = MstParentChecklists::where('type_checklist', $type)->get();
        $listMarks   = MstDropdowns::where('category', 'Type Mark Checklist')->get();

        $typePerCheck      = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $useGuideChecklist = in_array($type, $typePerCheck);

        //Audit Log
        $this->auditLogsShort('View List Mst Checklist');

        return view('checklist.index', compact('type', 'listParents', 'listMarks', 'useGuideChecklist'));
    }

    // DETAIL CHECKLIST
    public function detail($id)
    {
        $id = decrypt($id);

        $checklist  = MstChecklists::where('id', $id)->first();
        $parent     = MstParentChecklists::where('id', $checklist->id_parent_checklist)->first();
        $mark       = MstChecklistDetails::where('id_checklist', $id)->get();
        $orders     = MstChecklists::select('order_no', 'sub_point_checklist')
            ->where('id_parent_checklist', $checklist->id_parent_checklist)
            ->orderBy('order_no', 'asc')->get();

        $listTypeChecklists = MstDropdowns::where('category', 'Type Checklist')->get();
        $listTypeParent     = MstParentChecklists::where('type_checklist', $parent->type_checklist)->get();
        $listMarks          = MstDropdowns::where('category', 'Type Mark Checklist')->get();
        $typePerCheck       = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();

        //Audit Log
        $this->auditLogsShort("View Info Checklist ID: {$id}");

        return view('checklist.detail', compact(
            'id', 'checklist', 'parent', 'mark', 'orders', 'listTypeChecklists', 'listTypeParent', 'listMarks', 'typePerCheck'
        ));
    }
    
    // STORE NEW CHECKLIST
    public function store(Request $request)
    {
        $typePerCheck    = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $isGuideRequired = in_array($request->type_checklist, $typePerCheck);
        $uploadFile      = $isGuideRequired ? 1 : 0;

        $request->validate([
            'type_checklist'         => 'required',
            'parent_point_checklist' => 'required',
            'sub_point_checklist'    => 'required',
            'indikator'              => 'required',
            'mandatory_silver'       => 'required',
            'mandatory_gold'         => 'required',
            'mandatory_platinum'     => 'required',
            'guide_checklist'        => $isGuideRequired
                ? 'required|image|mimes:jpg,jpeg,png|max:10240'
                : 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $pathGuide = null;
            // Upload file if exists
            if ($request->hasFile('guide_checklist')) {
                $file      = $request->file('guide_checklist');
                $pathGuide = Storage::disk('s3')->putFileAs(
                    'guide/checklist', $file, time() . '-1_' . $file->getClientOriginalName()
                );
            }
            
            // Lock rows to prevent race condition
            $lastOrderNo = MstChecklists::where('id_parent_checklist', $request->parent_point_checklist)
                ->lockForUpdate()
                ->max('order_no');
            $orderNo = $lastOrderNo ? $lastOrderNo + 1 : 1;

            // Store Checklist
            $store = MstChecklists::create([
                'order_no'              => $orderNo,
                'id_parent_checklist'   => $request->parent_point_checklist,
                'child_point_checklist' => $request->child_point_checklist,
                'sub_point_checklist'   => $request->sub_point_checklist,
                'indikator'             => $request->indikator,
                'mandatory_silver'      => $request->mandatory_silver,
                'mandatory_gold'        => $request->mandatory_gold,
                'mandatory_platinum'    => $request->mandatory_platinum,
                'upload_file'           => $uploadFile,
                'path_guide_checklist'  => $pathGuide,
            ]);

            // Store Mark
            foreach ($request->meta_name as $item) {
                $mark = MstDropdowns::where('id', $item)->first();
                if ($mark) {
                    MstChecklistDetails::create([
                        'id_checklist' => $store->id,
                        'result'       => $mark->code_format,
                        'meta_name'    => $mark->name_value,
                        'meta_value'   => 1,
                    ]);
                }
            }

            // Audit Log
            $this->auditLogsShort("Create New Checklist ID: {$store->id}");

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Checklist']);
        } catch (Exception $e) {
            DB::rollback();

            // Delete uploaded file if transaction fails
            if (!empty($pathGuide) && Storage::disk('s3')->exists($pathGuide)) {
                Storage::disk('s3')->delete($pathGuide);
            }
            \Log::error('Store Checklist Error: ' . $e->getMessage());

            return redirect()->back()->with(['fail' => 'Failed to Create New Checklist!']);
        }
    }

    // UPDATE CHECKLIST (PARENT / ORDER / FILE)
    public function updateHeadCheck(Request $request, $id)
    {
        $id           = decrypt($id);
        $newGuidePath = null;

        $request->validate([
            'parent_point_checklist_current' => 'required',
            'order_current'          => 'required',
            'type_checklist'         => 'required',
            'parent_point_checklist' => 'required',
            'order_no'               => 'required',
            'guide_checklist'        => 'nullable|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        $checklist       = MstChecklists::findOrFail($id);
        $oldGuidePath    = $checklist->path_guide_checklist;
        $typePerCheck    = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $isGuideRequired = in_array($request->type_checklist, $typePerCheck);

        if ($isGuideRequired && !$oldGuidePath && !$request->hasFile('guide_checklist')) {
            return back()->with(['fail' => 'Failed!, You Have Upload Guide Checklist IF Type Premises']);
        }

        DB::beginTransaction();
        try {
            // Upload Guide File
            if ($request->hasFile('guide_checklist')) {
                $file = $request->file('guide_checklist');
                $newGuidePath  = Storage::disk('s3')->putFileAs(
                    'guide/checklist', $file, time() . '-1_' . $file->getClientOriginalName()
                );
                $pathGuide = $newGuidePath;
            } else {
                $pathGuide = $oldGuidePath;
            }

            // Detect changes
            $checklist->fill([
                'order_no'               => $request->order_no,
                'id_parent_checklist'    => $request->parent_point_checklist,
                'path_guide_checklist'   => $pathGuide
            ]);

            // If no changes return
            if (!$checklist->isDirty()) {
                return back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
            }

            $reqOrder = $request->order_no;
            if (!in_array($reqOrder, ['0', '99999'])) {
                // If switch with other parent, update order destination parent with current order
                $target = MstChecklists::where('id_parent_checklist', $request->parent_point_checklist)
                    ->where('order_no', $reqOrder)
                    ->first();
                if ($target) {
                    $target->update([
                        'order_no' => $request->order_current
                    ]);
                }
            }

            // Update checklist
            $checklist->update([
                'order_no'               => $reqOrder,
                'id_parent_checklist'    => $request->parent_point_checklist,
                'path_guide_checklist'   => $pathGuide
            ]);

            // Reindex order
            $this->reindexSubPoint($request->parent_point_checklist);
            if ($request->parent_point_checklist !== $request->parent_point_checklist_current) {
                $this->reindexSubPoint($request->parent_point_checklist_current);
            }

            // Delete old file (when uploaded new and success update)
            if ($request->hasFile('guide_checklist') && $oldGuidePath) {
                if (Storage::disk('s3')->exists($oldGuidePath)) {
                    Storage::disk('s3')->delete($oldGuidePath);
                }
            }

            // Audit Log
            $this->auditLogsShort('Update Parent Detail or Order Number Checklist ID (' . $id . ')');

            DB::commit();
            return back()->with(['success' => 'Success Update Parent Checklist']);
        } catch (Exception $e) {
            DB::rollback();

            // Delete New Uploaded File if Error
            if ($newGuidePath && Storage::disk('s3')->exists($newGuidePath)) {
                Storage::disk('s3')->delete($newGuidePath);
            }

            \Log::error('Update Parent Detail or Order Number Checklist Error: ' . $e->getMessage());
            return back()->with(['fail' => 'Failed to Update Parent Detail or Order Number Checklist!']);
        }
    }

    // UPDATE CHECKLIST DETAIL
    public function updateCheckDetail(Request $request, $id)
    {
        $id = decrypt($id);
        
        $request->validate([
            'sub_point_checklist' => 'required',
            'indikator'           => 'required',
            'mandatory_silver'    => 'required',
            'mandatory_gold'      => 'required',
            'mandatory_platinum'  => 'required',
        ]);

        $checklist = MstChecklists::findOrFail($id);
        // Detect changes
        $checklist->fill([
            'child_point_checklist' => $request->child_point_checklist,
            'sub_point_checklist'   => $request->sub_point_checklist,
            'indikator'             => $request->indikator,
            'mandatory_silver'      => $request->mandatory_silver,
            'mandatory_gold'        => $request->mandatory_gold,
            'mandatory_platinum'    => $request->mandatory_platinum,
        ]);

        // If no changes return
        if (!$checklist->isDirty()) {
            return back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
        
        DB::beginTransaction();
        try {
            // Update checklist
            $checklist->update([
                'child_point_checklist' => $request->child_point_checklist,
                'sub_point_checklist'   => $request->sub_point_checklist,
                'indikator'             => $request->indikator,
                'mandatory_silver'      => $request->mandatory_silver,
                'mandatory_gold'        => $request->mandatory_gold,
                'mandatory_platinum'    => $request->mandatory_platinum,
            ]);

            // Audit Log
            $this->auditLogsShort('Update Detail Checklist ID (' . $id . ')');

            DB::commit();
            return back()->with(['success' => 'Success Update Detail Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Update Detail Checklist Error: ' . $e->getMessage());

            return back()->with(['fail' => 'Failed to Update Detail Checklist!']);
        }
    }

    // UPDATE CHECKLIST MARK
    public function updateMark(Request $request, $id)
    {
        $id = decrypt($id);

        // Check Different (Compare With Data Before)
        $markBefore        = MstChecklistDetails::select('meta_name as mark')->where('id_checklist', $id)->get()->toArray();
        $markRequest       = MstDropdowns::select('name_value as mark')->where('category', 'Type Mark Checklist')->whereIn('id', $request->meta_name)->get()->toArray();
        $markBeforeValues  = array_column($markBefore, 'mark');
        $markRequestValues = array_column($markRequest, 'mark');
        
        sort($markBeforeValues);
        sort($markRequestValues);

        if ($markBeforeValues == $markRequestValues) {
            return back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }

        DB::beginTransaction();
        try {
            // Delete Not In Request If Any
            MstChecklistDetails::where('id_checklist', $id)->whereNotIn('meta_name', $markRequest)->delete();
            // Create IF Still Not Exist Beside Request
            foreach ($request->meta_name as $idmetaName) {
                $mark = MstDropdowns::where('id', $idmetaName)->first();
                $valueName = $mark->name_value;
                $codeFormat = $mark->code_format;

                MstChecklistDetails::firstOrCreate([
                    'id_checklist' => $id,
                    'meta_name'    => $valueName,
                ], [
                    'result'     => $codeFormat,
                    'meta_value' => '1'
                ]);
            }

            //Audit Log
            $this->auditLogsShort('Update Mark Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Update Mark Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            \Log::error('Update Mark Checklist Error: ' . $e->getMessage());

            return redirect()->back()->with(['fail' => 'Failed to Update Mark Checklist!']);
        }
    }

    // AJAX FIND LIST ORDER NO BY TYPE CHECKLIST & PARENT CHECKLIST
    public function mappingOrderNo($parentPoint, $typeChecklist)
    {
        $orders = MstChecklists::join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist')
            ->where('mst_parent_checklists.type_checklist', $typeChecklist)
            ->where('id_parent_checklist', $parentPoint)
            ->orderby('mst_checklists.id_parent_checklist')
            ->orderby('mst_parent_checklists.parent_point_checklist')
            ->orderby('mst_checklists.order_no')
            ->get();
        return response()->json($orders);
    }

    // AJAX FIND LIST PARENT CHECKLIST BY TYPE CHECKLIST
    public function mappingparent($name)
    {
        $parents = MstParentChecklists::where('type_checklist', $name)->get();
        return $parents;
    }
}

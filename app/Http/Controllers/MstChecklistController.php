<?php

namespace App\Http\Controllers;

use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

//model
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Models\MstParentChecklists;
use App\Traits\OrderTrait;

class MstChecklistController extends Controller
{
    use AuditLogsTrait, OrderTrait;

    public function typechecklist(Request $request)
    {
        $datas = MstDropdowns::select('name_value')->where('category', 'Type Checklist')->get();

        foreach ($datas as $data) {
            $amount = MstChecklists::leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
                ->where('mst_parent_checklists.type_checklist', $data->name_value)
                ->count();
            $data->amount = $amount;
        }

        if ($request->ajax()) {
            $data = DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('checklist.type.action', compact('data'));
                })
                ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Type Checklist');

        return view('checklist.type.index');
    }

    public function index(Request $request, $type)
    {
        $datas = MstChecklists::join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist')
            ->where('mst_parent_checklists.type_checklist', $type)
            // ->orderby('mst_checklists.id_parent_checklist')
            // ->orderby('mst_parent_checklists.parent_point_checklist')
            ->orderby('mst_parent_checklists.order_no')
            ->orderby('mst_checklists.order_no')
            ->get();
        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $type_parent = MstParentChecklists::where('type_checklist', $type)->get();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('checklist.action', compact('data'));
                })
                ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Checklist');

        return view('checklist.index', compact('datas', 'type', 'type_checklist', 'type_parent'));
    }

    public function mappingparent($name)
    {
        $parents = MstParentChecklists::where('type_checklist', $name)->get();
        return $parents;
    }

    public function info($id)
    {
        $id = decrypt($id);

        $checklist = MstChecklists::where('id', $id)->first();
        $parent = MstParentChecklists::where('id', $checklist->id_parent_checklist)->first();

        //Audit Log
        $this->auditLogsShort('View Info ID (' . $id . ')');

        return view('checklist.info', compact('checklist', 'parent'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'type_checklist' => 'required',
            'parent_point_checklist' => 'required',
            'sub_point_checklist' => 'required',
            'indikator' => 'required',
            'mandatory_silver' => 'required',
            'mandatory_gold' => 'required',
            'mandatory_platinum' => 'required',

            'guide_checklist' => $request->type_checklist == 'H1 Premises' ? 'required|image|mimes:jpg,jpeg,png|max:3072' : '',
        ]);

        DB::beginTransaction();
        try {
            // IF Create Checklist With Type H1 Premises (Conditional HardCode)
            if ($request->type_checklist == 'H1 Premises') {
                $uploadfile = 1;
                if ($request->hasFile('guide_checklist')) {
                    $path_loc = $request->file('guide_checklist');
                    $name = $path_loc->hashName();
                    $path_loc->move(public_path('assets/images/guidechecklist'), $name);
                    $url_guide_check = 'assets/images/guidechecklist/' . $name;
                } else {
                    $url_guide_check = null;
                    return redirect()->back()->with(['fail' => 'Failed to Save File!']);
                }
            } else {
                $uploadfile = 0;
                $url_guide_check = null;
            }

            // Check Add New Parent or Not
            if ($request->parent_point_checklist == "AddParent") {
                // Store Image Tumbnail & Get Path URL
                $request->validate([
                    'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:3072'
                ]);
                if ($request->hasFile('thumbnail')) {
                    $path_loc_thumb = $request->file('thumbnail');
                    $name = $path_loc_thumb->hashName();
                    $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                    $url_thumb = 'assets/images/thumbnails/' . $name;
                } else {
                    $url_thumb = null;
                    return redirect()->back()->with(['fail' => 'Failed to Save File!']);
                }
                // Store New Parent
                $newParentChecklist = MstParentChecklists::create([
                    'type_checklist' => $request->type_checklist,
                    'parent_point_checklist' => $request->add_parent,
                    'path_guide_premises' => $url_thumb
                ]);
                $id_parent = $newParentChecklist->id;
            } else {
                $id_parent = $request->parent_point_checklist;
            }

            if ($request->q_child_point == "0") {
                $child_checklist = null;
            } elseif ($request->q_child_point == "1") {
                $child_checklist = $request->child_checklist;
            }

            //cek last order no first
            $last_order_no = MstChecklists::where('id_parent_checklist', $id_parent)->orderBy('order_no', 'desc')->first();
            $order_no = $last_order_no->order_no + 1;

            // Store Checklist
            MstChecklists::create([
                'order_no' => $order_no,
                'id_parent_checklist' => $id_parent,
                'child_point_checklist' => $child_checklist,
                'sub_point_checklist' => $request->sub_point_checklist,
                'indikator' => $request->indikator,
                'mandatory_silver' => $request->mandatory_silver,
                'mandatory_gold' => $request->mandatory_gold,
                'mandatory_platinum' => $request->mandatory_platinum,
                'upload_file' => $uploadfile,
                'path_guide_checklist' => $url_guide_check,
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Checklist');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Checklist!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $checklist = MstChecklists::where('id', $id)->first();
        $parent = MstParentChecklists::where('id', $checklist->id_parent_checklist)->first();

        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $type_parent = MstParentChecklists::where('type_checklist', $parent->type_checklist)->get();

        //Audit Log
        $this->auditLogsShort('View Edit Checklist ID (' . $id . ')');

        return view('checklist.edit', compact('checklist', 'parent', 'type_checklist', 'type_parent'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);

        $request->validate([
            'type_checklist' => 'required',
            'parent_point_checklist' => 'required',
            'sub_point_checklist' => 'required',
            'indikator' => 'required',
            'mandatory_silver' => 'required',
            'mandatory_gold' => 'required',
            'mandatory_platinum' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Check Data Before H1 Premises or not
            if ($request->type_checklist_before == 'H1 Premises') {
                $path_guide_checklist = MstChecklists::where('id', $id)->first()->path_guide_checklist;
                // IF Update Checklist With Type H1 Pemises (Conditional HardCode)
                if ($request->type_checklist == 'H1 Premises') {
                    $uploadfile = 1;
                    if ($request->hasFile('guide_checklist')) {
                        $path_loc = $request->file('guide_checklist');
                        $name = $path_loc->hashName();
                        $path_loc->move(public_path('assets/images/guidechecklist'), $name);
                        $url_guide_check = 'assets/images/guidechecklist/' . $name;
                    } else {
                        $url_guide_check = $path_guide_checklist;
                    }
                } else {
                    $uploadfile = 0;
                    $url_guide_check = null;
                }
            } else {
                if ($request->type_checklist == 'H1 Premises') {
                    $uploadfile = 1;
                    if ($request->hasFile('guide_checklist')) {
                        $path_loc = $request->file('guide_checklist');
                        $name = $path_loc->hashName();
                        $path_loc->move(public_path('assets/images/guidechecklist'), $name);
                        $url_guide_check = 'assets/images/guidechecklist/' . $name;
                    } else {
                        $url_guide_check = null;
                        return redirect()->back()->with(['fail' => 'Failed to Save File!']);
                    }
                } else {
                    $uploadfile = 0;
                    $url_guide_check = null;
                }
            }

            // Check Add New Parent or Not
            if ($request->parent_point_checklist == "AddParent") {
                // Store Image Tumbnail & Get Path URL
                $request->validate([
                    'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:3072'
                ]);
                if ($request->hasFile('thumbnail')) {
                    $path_loc_thumb = $request->file('thumbnail');
                    $name = $path_loc_thumb->hashName();
                    $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                    $url_thumb = 'assets/images/thumbnails/' . $name;
                } else {
                    $url_thumb = null;
                    return redirect()->back()->with(['fail' => 'Failed to Save File!']);
                }
                // Store New Parent
                $newParentChecklist = MstParentChecklists::create([
                    'type_checklist' => $request->type_checklist,
                    'parent_point_checklist' => $request->add_parent,
                    'path_guide_premises' => $url_thumb
                ]);
                $id_parent = $newParentChecklist->id;
            } else {
                $id_parent = $request->parent_point_checklist;
            }

            if ($request->q_child_point == "0") {
                $child_checklist = null;
            } elseif ($request->q_child_point == "1") {
                $child_checklist = $request->child_checklist;
            }

            $databefore = MstChecklists::where('id', $id)->first();

            $databefore->id_parent_checklist = $id_parent;
            $databefore->child_point_checklist = $child_checklist;
            $databefore->sub_point_checklist = $request->sub_point_checklist;
            $databefore->indikator = $request->indikator;
            $databefore->mandatory_silver = $request->mandatory_silver;
            $databefore->mandatory_gold = $request->mandatory_gold;
            $databefore->mandatory_platinum = $request->mandatory_platinum;
            $databefore->path_guide_checklist = $url_guide_check;

            if ($databefore->isDirty()) {
                // Update Checklist
                MstChecklists::where('id', $id)->update([
                    'id_parent_checklist' => $id_parent,
                    'child_point_checklist' => $child_checklist,
                    'sub_point_checklist' => $request->sub_point_checklist,
                    'indikator' => $request->indikator,
                    'mandatory_silver' => $request->mandatory_silver,
                    'mandatory_gold' => $request->mandatory_gold,
                    'mandatory_platinum' => $request->mandatory_platinum,
                    'upload_file' => $uploadfile,
                    'path_guide_checklist' => $url_guide_check,
                ]);
            } else {
                return redirect()->route('checklist.index', $request->type_checklist)->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
            }

            //Audit Log
            $this->auditLogsShort('Update Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()->route('checklist.index', $request->type_checklist)->with(['success' => 'Success Update Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Update Checklist!']);
        }
    }

    public function mark(Request $request, $id)
    {
        $id = decrypt($id);

        $datas = MstChecklistDetails::where('id_checklist', $id)->get();
        $type_mark = MstDropdowns::where('category', 'Type Mark Checklist')->get();
        $checklist = MstChecklists::select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist', 'mst_parent_checklists.path_guide_premises')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->where('mst_checklists.id', $id)
            ->first();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('checklist.mark.action', compact('data'));
                })
                ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mark Checklist (' . $checklist->parent_point_checklist . ')');

        return view('checklist.mark.index', compact('datas', 'type_mark', 'id', 'checklist'));
    }

    public function markstore(Request $request, $id)
    {
        $id = decrypt($id);

        $request->validate([
            'meta_name' => 'required|array|min:1',
            'meta_name.*' => 'exists:mst_dropdowns,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->meta_name as $idmetaName) {
                $mark = MstDropdowns::where('id', $idmetaName)->first();
                $valueName = $mark->name_value;
                $codeFormat = $mark->code_format;

                MstChecklistDetails::firstOrCreate([
                    'id_checklist' => $id,
                    'meta_name' => $valueName,
                ], [
                    'result' => $codeFormat,
                    'meta_value' => '1'
                ]);
            }

            //Audit Log
            $this->auditLogsShort('Update New Mark Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Update New Mark Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Mark Checklist!']);
        }
    }

    public function markdelete($id)
    {
        $id = decrypt($id);

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            MstChecklistDetails::findOrFail($id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Mark Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Mark Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Mark Checklist!']);
        }
    }

    public function exchangeOrder($id)
    {
        $id = decrypt($id);
        $checklist = MstChecklists::select(
            'mst_checklists.*',
            'mst_parent_checklists.type_checklist',
            'mst_parent_checklists.parent_point_checklist'
        )
            ->where('mst_checklists.id', $id)
            ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->first();

        //dd($checklist);
        $parent = MstParentChecklists::where('id', $checklist->id_parent_checklist)->first();

        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $type_parent = MstParentChecklists::where('type_checklist', $parent->type_checklist)->get();

        $orders = MstChecklists::select(
            'mst_checklists.order_no',
            'sub_point_checklist'
        )
            ->leftJoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', 'mst_parent_checklists.id')
            ->where('parent_point_checklist', $checklist->parent_point_checklist)
            ->orderBy('order_no', 'asc')
            ->get();
        //Audit Log
        $this->auditLogsShort('View Exchange Order Number (' . $id . ')');

        return view('checklist.exc_order', compact('checklist', 'parent', 'type_checklist', 'type_parent', 'orders'));
    }

    public function exchangeOrderUpdate(Request $request, $id)
    {
        $id = decrypt($id);
        $req_order_no = $request->order_no;
        //dd($request->all(),$id);
        if ($req_order_no == '0' || $req_order_no == '99999') { //kalau pilih sebagai awal atau akhir
            // update dengan order no baru
            MstChecklists::where('id', $id)->update([
                'order_no' => $req_order_no
            ]);
        } else {
            //parent point checklist di tukar
            $parentPoint_target = MstChecklists::where('id_parent_checklist', $request->parent_point_checklist)
                ->where('order_no', $req_order_no)
                ->first();

            //cari checklist dengan order number dituju
            MstChecklists::where('id', $parentPoint_target->id)
                ->update([
                    'order_no' => $request->order_current,
                    'id_parent_checklist' => $request->parent_point_checklist_current
                ]);

            // update dengan order no baru
            MstChecklists::where('id', $id)
                ->update([
                    'order_no' => $req_order_no,
                    'id_parent_checklist' => $request->parent_point_checklist
                ]);
        }

        //reindex supaya gak ada order no yg skip
        $this->reindexSubPoint($request->parent_point_checklist);
        $this->reindexSubPoint($request->parent_point_checklist_current);

        return redirect()->route('checklist.index', $request->type_checklist)->with(['success' => 'Success Exchange Order Number']);
    }

    public function mappingOrderNo($parentPoint, $typeChecklist)
    {
        //dd($parentPoint,$typeChecklist);
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
}

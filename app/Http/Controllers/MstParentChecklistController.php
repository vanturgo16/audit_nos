<?php

namespace App\Http\Controllers;

use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;

//model
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Models\MstParentChecklists;
use App\Models\MstRules;
use App\Traits\OrderTrait;


class MstParentChecklistController extends Controller
{
    use AuditLogsTrait, OrderTrait;

    public function typechecklist(Request $request)
    {
        $datas = MstDropdowns::select('name_value')->where('category', 'Type Checklist')->get();
        foreach ($datas as $data) {
            $amount = MstParentChecklists::where('type_checklist', $data->name_value)->count();
            $data->amount = $amount;
        }

        if ($request->ajax()) {
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

    public function index(Request $request, $type)
    {
        $datas = MstParentChecklists::where('type_checklist', $type)
            ->orderBy('type_checklist', 'asc')
            ->orderBy('order_no', 'asc')
            ->get();
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('parentchecklist.action', compact('data'));
                })
                ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Parent Checklist');

        return view('parentchecklist.index', compact('datas', 'type', 'typeChecklistPerCheck'));
    }

    public function detail($id)
    {
        $id = decrypt($id);
        $parent = MstParentChecklists::where('id', $id)->first();
        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $orders = MstParentChecklists::where('type_checklist', $parent->type_checklist)->orderBy('order_no', 'asc')->get();
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();

        //Audit Log
        $this->auditLogsShort('View Detail Parent Checklist ID (' . $id . ')');

        return view('parentchecklist.detail', compact('id', 'parent', 'type_checklist', 'orders', 'typeChecklistPerCheck'));
    }

    public function updateParent(Request $request, $id)
    {
        $id = decrypt($id);
        $request->validate([
            'type_checklist' => 'required',
            'add_parent' => 'required',
            'order_no' => 'required',
        ]);

        // Check Data Change
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $dataBefore = MstParentChecklists::where('id', $id)->first();
        if (!in_array($request->type_checklist, $typeChecklistPerCheck) && $dataBefore->path_guide_premises == null && !$request->hasFile('guide_parent')) {
            return redirect()->back()->with(['fail' => 'Failed! You must upload Guide Parent Checklist if Type is not in the checklist rules.']);
        }

        $dataBefore->type_checklist = $request->type_checklist;
        $dataBefore->parent_point_checklist = $request->add_parent;
        $dataBefore->order_no = $request->order_no;

        if ($dataBefore->isDirty() || $request->hasFile('guide_parent')) {
            DB::beginTransaction();
            try {
                // GET FILE URL IF EXIST
                if ($request->hasFile('guide_parent')) {
                    $path_loc = $request->file('guide_parent');
                    $name = $path_loc->hashName();
                    $path_loc->move(public_path('assets/images/thumbnail'), $name);
                    $url_guide_parent = 'assets/images/thumbnail/' . $name;
                } else {
                    $url_guide_parent = $dataBefore->path_guide_premises;
                }

                // UPDATE DETAIL PARENT
                $req_order_no = $request->order_no;
                if ($req_order_no == '0' || $req_order_no == '99999') { //kalau pilih sebagai awal atau akhir
                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_guide_parent
                    ]);
                } else {
                    //parent point checklist di tukar
                    $parentPoint_target = MstParentChecklists::where('type_checklist', $request->type_checklist)
                        ->where('order_no', $req_order_no)
                        ->first();

                    //cari checklist dengan order number dituju
                    MstParentChecklists::where('id', $parentPoint_target->id)
                        ->update([
                            'order_no' => $request->order_current
                        ]);

                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_guide_parent
                    ]);

                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_guide_parent
                    ]);
                }

                //reindex supaya gak ada order no yg skip
                $this->reindexParentPoint($request->type_checklist);
                $this->reindexParentPoint($request->type_checklist_current);

                //Audit Log
                $this->auditLogsShort('Update Parent Detail or Order Number Parent Checklist ID (' . $id . ')');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Parent Detail or Order Number Parent Checklist']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Parent Detail or Order Number Parent Checklist!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }


    public function info($id)
    {
        $id = decrypt($id);

        $parent = MstParentChecklists::where('id', $id)->first();

        //Audit Log
        $this->auditLogsShort('View Info Parent Checklist ID (' . $id . ')');

        return view('parentchecklist.info', compact('parent'));
    }

    public function store(Request $request)
    {
        $typeChecklistPerCheck = MstRules::where('rule_name', 'Type Checklist Per Checklist')->pluck('rule_value')->toArray();
        $request->validate([
            'type_checklist' => 'required',
            'add_parent' => 'required',
            'thumbnail' => !in_array($request->type_checklist, $typeChecklistPerCheck) 
                ? 'required|image|mimes:jpg,jpeg,png|max:3072' 
                : ''
        ]);
    

        DB::beginTransaction();
        try {
            // Store Image Tumbnail & Get Path URL
            if ($request->hasFile('thumbnail')) {
                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb->hashName();
                $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                $url_thumb = 'assets/images/thumbnails/' . $name;
            } else {
                $url_thumb = null;
            }

            //cek last order no first
            $last_order_no = MstParentChecklists::where('type_checklist', $request->type_checklist)->orderBy('order_no', 'desc')->first();
            if ($last_order_no) {
                $order_no = $last_order_no->order_no + 1;
            } else {
                $order_no = 1;
            }

            // Store New Parent
            MstParentChecklists::create([
                'order_no' => $order_no,
                'type_checklist' => $request->type_checklist,
                'parent_point_checklist' => $request->add_parent,
                'path_guide_premises' => $url_thumb
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Parent Checklist ID');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Parent Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Parent Checklist!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);

        $parent = MstParentChecklists::where('id', $id)->first();
        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $orders = MstParentChecklists::where('type_checklist', $parent->type_checklist)
            ->orderBy('order_no', 'asc')
            ->get();

        //Audit Log
        $this->auditLogsShort('View Edit Parent Checklist ID (' . $id . ')');

        return view('parentchecklist.edit', compact('parent', 'type_checklist', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);

        $request->validate([
            'type_checklist' => 'required',
            'add_parent' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $databefore = MstParentChecklists::where('id', $id)->first();

            // Store Image Tumbnail & Get Path URL if exist
            if ($request->hasFile('thumbnail')) {
                //Delete File Before
                $path_before = MstParentChecklists::where('id', $id)->first()->path_guide_premises;
                if ($path_before != null) {
                    $file_path = public_path($path_before);
                    if (File::exists($file_path)) {
                        File::delete($file_path);
                    }
                }

                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb->hashName();
                $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                $url_thumb = 'assets/images/thumbnails/' . $name;
            } else {
                $url_thumb = $databefore->path_guide_premises;
            }

            $databefore->order_no = $request->order_no;
            $databefore->type_checklist = $request->type_checklist;
            $databefore->parent_point_checklist = $request->add_parent;
            $databefore->path_guide_premises = $url_thumb;
            if ($databefore->isDirty()) {
                // Update Parent
                $req_order_no = $request->order_no;
                if ($req_order_no == '0' || $req_order_no == '99999') { //kalau pilih sebagai awal atau akhir
                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_thumb
                    ]);
                } else {
                    //parent point checklist di tukar
                    $parentPoint_target = MstParentChecklists::where('type_checklist', $request->type_checklist)
                        ->where('order_no', $req_order_no)
                        ->first();

                    //cari checklist dengan order number dituju
                    MstParentChecklists::where('id', $parentPoint_target->id)
                        ->update([
                            'order_no' => $request->order_current
                        ]);

                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_thumb
                    ]);

                    // update dengan order no baru
                    MstParentChecklists::where('id', $id)->update([
                        'order_no' => $req_order_no,
                        'type_checklist' => $request->type_checklist,
                        'parent_point_checklist' => $request->add_parent,
                        'path_guide_premises' => $url_thumb
                    ]);
                }

                //reindex supaya gak ada order no yg skip
                $this->reindexParentPoint($request->type_checklist);
                $this->reindexParentPoint($request->type_checklist_current);
            } else {
                return redirect()->route('parentchecklist.index', $request->type_checklist)->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
            }

            //Audit Log
            $this->auditLogsShort('Update Parent Checklist ID (' . $id . ')');

            DB::commit();
            return redirect()->route('parentchecklist.index', $request->type_checklist)->with(['success' => 'Success Update Parent Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Update Parent Checklist!']);
        }
    }

    public function mappingOrderNo($type_checklist)
    {
        $orders = MstParentChecklists::where('type_checklist', $type_checklist)
            ->orderBy('order_no', 'asc')
            ->get();
        return response()->json($orders);
    }
}

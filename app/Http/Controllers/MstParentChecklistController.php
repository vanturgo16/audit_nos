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

class MstParentChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $type_checklist = MstDropdowns::where('category', 'Type Checklist')->get();
        $datas = MstParentChecklists::get();

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
        
        return view('parentchecklist.index',compact('datas', 'type_checklist'));
    }

    public function info($id)
    {
        $id = decrypt($id);

        $parent = MstParentChecklists::where('id', $id)->first();
        
        //Audit Log
        $this->auditLogsShort('View Info Parent Checklist ID ('. $id . ')');

        return view('parentchecklist.info',compact('parent'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'type_checklist' => 'required',
            'add_parent' => 'required',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:3072'
        ]);

        DB::beginTransaction();
        try{
            // Store Image Tumbnail & Get Path URL
            if($request->hasFile('thumbnail')){
                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                $url_thumb = 'assets/images/thumbnails/'.$name;
            }else{
                $url_thumb = null;
                return redirect()->back()->with(['fail' => 'Failed to Save File!']); 
            }
            // Store New Parent
            MstParentChecklists::create([
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
        
        //Audit Log
        $this->auditLogsShort('View Edit Parent Checklist ID ('. $id . ')');

        return view('parentchecklist.edit',compact('parent', 'type_checklist'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);

        $request->validate([
            'type_checklist' => 'required',
            'add_parent' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $databefore = MstParentChecklists::where('id', $id)->first();

            // Store Image Tumbnail & Get Path URL if exist
            if($request->hasFile('thumbnail')){
                //Delete File Before
                $path_before = MstParentChecklists::where('id', $id)->first()->path_guide_premises;
                if($path_before != null){
                    $file_path = public_path($path_before);
                    if (File::exists($file_path)) {
                        File::delete($file_path);
                    }
                }

                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                $url_thumb = 'assets/images/thumbnails/'.$name;
            } else{
                $url_thumb = $databefore->path_guide_premises;
            }

            $databefore->type_checklist = $request->type_checklist;
            $databefore->parent_point_checklist = $request->add_parent;
            $databefore->path_guide_premises = $url_thumb;

            if($databefore->isDirty()){
                // Update Parent
                MstParentChecklists::where('id', $id)->update([
                    'type_checklist' => $request->type_checklist,
                    'parent_point_checklist' => $request->add_parent,
                    'path_guide_premises' => $url_thumb
                ]);
            } else {
                return redirect()->route('parentchecklist.index')->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
            }

            //Audit Log
            $this->auditLogsShort('Update Parent Checklist ID ('. $id . ')');

            DB::commit();
            return redirect()->route('parentchecklist.index')->with(['success' => 'Success Update Parent Checklist']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Update Parent Checklist!']);
        }
    }
}

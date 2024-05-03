<?php

namespace App\Http\Controllers;

use App\Models\MstChecklistDetails;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Intervention\Image\Facades\Image;
use Browser;

//model
use App\Models\MstChecklists;
use App\Models\MstDropdowns;
use App\Models\MstParentChecklists;

class MstChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {

        // $datas=MstChecklists::get();
        $datas = MstChecklists::join('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
        ->select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist', 'mst_parent_checklists.path_guide_premises')
        ->get();
        $type_checklist = MstDropdowns ::where('category', 'Type Checklist')->get();
        $type_parent = MstParentChecklists ::get();

        //Audit Log
        $this->auditLogsShort('View List Mst Checklist');
        
        return view('checklist.index',compact('datas', 'type_checklist', 'type_parent'));
        // dd($datas);
    }
    public function store(Request $request)
    {

        $request->validate([
            'type_checklist' => 'required',
            'parent_point_checklist' => 'required',
            'q_child_point' => 'required',
            'sub_point_checklist' => 'required',
            'indikator' => 'required',
            'mandatory_silver' => 'required',
            'mandatory_gold' => 'required',
            'mandatory_platinum' => 'required',
            // 'upload_file' => 'required'
        ]);

        if($request->parent_point_checklist == "AddParent"){
            
            $add_parent = $request->add_parent;
            $request->validate([
                'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:3072'
            ]);
            // $imagePath = $request->file('thumbnail')->store('images');
            // $url = asset('thumbnails/', $imagePath);
            if($request->hasFile('thumbnail')){
                $path_loc_thumb = $request->file('thumbnail');
                $name = $path_loc_thumb ->hashName();
                $path_loc_thumb->move(public_path('assets/images/thumbnails'), $name);
                $url_thumb = 'assets/images/thumbnails/'.$name;
            }else{
                $url_thumb = null;
                return redirect()->back()->with(['fail' => 'Failed to Save File!']); 
            }
// dd($url_thumb);
            // $image = Image::make($request->file('thumbnail')->getRealPath());
            // $extension = $request->file('thumbnail')->getClientOriginalExtension();
            // $image->stream($extension, 80);
            // $imagePath = $request->file('thumbnail')->storeAs('images/thumbnails', $request->file('thumbnail')->hashName(), 'public');
            // Storage::delete($request->file('thumbnail')->hashName());

                DB::beginTransaction();
            try{
                
                $newParentChecklist =   MstParentChecklists::create([
                                            'type_checklist' => $request->type_checklist,
                                            'parent_point_checklist' => $add_parent,
                                            'path_guide_premises' => $url_thumb
                                        ]);


                $id_parent = $newParentChecklist->id;
                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();
                dd($e);
            }

        }else{
            $id_parent = $request->parent_point_checklist;
        }

        if($request->q_child_point == "0"){
            $child_checklist = null;
        }elseif($request->q_child_point == "1"){
            $child_checklist = $request->child_checklist;
        }

        DB::beginTransaction();
        try{
            
            MstChecklists::create([
                'id_parent_checklist' => $id_parent,
                'child_point_checklist' => $child_checklist,
                'sub_point_checklist' => $request->sub_point_checklist,
                'indikator' => $request->indikator,
                'mandatory_silver' => $request->mandatory_silver,
                'mandatory_gold' => $request->mandatory_gold,
                'mandatory_platinum' => $request->mandatory_platinum,
                // 'upload_file' => $request->upload_file
                'upload_file' => 0
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Checklist!']);
        }
    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $request->validate([
            'type_checklist' => 'required',
            'point_checklist' => 'required',
            'sub_point_checklist' => 'required',
            'indikator' => 'required',
            'mandatory_silver' => 'required',
            'mandatory_gold' => 'required',
            'mandatory_platinum' => 'required',
            // 'upload_file' => 'required'
        ]);


        $databefore = MstChecklists::where('id', $id)->first();
        $databefore->type_checklist = $request->type_checklist;
        $databefore->point_checklist = $request->point_checklist;
        $databefore->sub_point_checklist = $request->sub_point_checklist;
        $databefore->indikator = $request->indikator;
        $databefore->mandatory_silver = $request->mandatory_silver;
        $databefore->mandatory_gold = $request->mandatory_gold;
        $databefore->mandatory_platinum = $request->mandatory_platinum;
        // $databefore->upload_file = $request->upload_file;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstChecklists::where('id', $id)->update([
                    'type_checklist' => $request->type_checklist,
                    'point_checklist' => $request->point_checklist,
                    'sub_point_checklist' => $request->sub_point_checklist,
                    'indikator' => $request->indikator,
                    'mandatory_silver' => $request->mandatory_silver,
                    'mandatory_gold' => $request->mandatory_gold,
                    'mandatory_platinum' => $request->mandatory_platinum,
                    // 'upload_file' => $request->upload_file
                    'upload_file' => 0
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Checklist';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Checklist']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Checklist!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function mark($id)
    {

        $id = decrypt($id);

        $datas = MstChecklistDetails ::where('id_checklist', $id)->get();
        $type_mark = MstDropdowns ::where('category', 'Type Mark Checklist')->get();
        $checklist=MstChecklists::select('mst_checklists.*', 'mst_parent_checklists.type_checklist', 'mst_parent_checklists.parent_point_checklist', 'mst_parent_checklists.path_guide_premises')
            ->leftjoin('mst_parent_checklists', 'mst_checklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
            ->where('mst_checklists.id', $id)
            ->first();

        //Audit Log
        $this->auditLogsShort('View List Mark Checklist ('. $checklist->parent_point_checklist . ')');

        return view('checklist.markcheck',compact('datas', 'type_mark', 'id', 'checklist'));
        // dd($datas);
    }
    
    public function markstore(Request $request, $id)
    {
        $id = decrypt($id);

        $request->validate([
            'meta_name' => 'required|array|min:1',
            'meta_name.*' => 'exists:mst_dropdowns,id',
        ]);

        
        
        DB::beginTransaction();
        try{
            

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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Mark Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Mark Checklist']);
        }catch (\Exception $e) {
        
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Mark Checklist!']);
        }
    }

    public function markdelete($id)
    {
        $id = decrypt($id);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $data = MstChecklistDetails::findOrFail($id)->delete();
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Mark Checklist';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);


            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Mark Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Mark Checklist!']);
        }
        
    }

}

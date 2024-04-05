<?php

namespace App\Http\Controllers;

use App\Models\MstDropdowns;
use App\Models\MstMapChecklists;
use App\Models\MstParentChecklists;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;

use Browser;
use Illuminate\Support\Facades\DB;

class MstMapChecklistController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {

        $datas = MstDropdowns::where('category', 'Type Dealer')->get();
        
        // dd($datas);
        

        //Audit Log
        $this->auditLogsShort('View List Type Jaringan Mst MapChecklist');
        
        return view('mapchecklist.index', compact('datas'));
    }
    public function type($type)
    {

        $type = decrypt($type);
        $datas = MstMapChecklists::select('type_checklist as type')
        ->where('mst_mapchecklists.type_jaringan', $type)
        ->join('mst_parent_checklists', 'mst_mapchecklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
        ->groupBy('mst_parent_checklists.type_checklist')
        ->selectRaw('COUNT(*) as count')
        ->get();

        $type_checklist = MstDropdowns::select('type_checklist as name_value')
        ->where('mst_dropdowns.category', 'Type Checklist')
        ->join('mst_parent_checklists', 'mst_dropdowns.name_value', '=', 'mst_parent_checklists.type_checklist')
        ->whereNotIn('mst_parent_checklists.id', function($query) use ($type) {
            $query->select('id_parent_checklist')
                ->from('mst_mapchecklists')
                ->where('type_jaringan', $type);
        })
        ->groupBy('mst_parent_checklists.type_checklist')
        ->get();


        // $type = decrypt($type);
        // dd($type_checklist);

        //Audit Log
        $this->auditLogsShort('View List Type Checklist Mst MapChecklist');
        
        return view('mapchecklist.typecheck', compact('datas', 'type_checklist', 'type'));
    }
    public function addtype($type, Request $request)
    {

        $type = decrypt($type);
        DB::beginTransaction();
        try{
            

            // menggunakan perulangan untuk input data
            foreach ($request->type_checklist as $type_checklist) {
                // echo $type_checklist . "<br>";

                // disini kita cari parent dengan type checklist yang diinputkan
                $parent = MstParentChecklists::where('type_checklist', $type_checklist)->get();
                // dd($parent);
                foreach($parent as $par){
                    $check = MstMapChecklists::where('id_parent_checklist', $par->id)
                    ->where('type_jaringan', $type)->first();
                    
                    if(!$check){

                        MstMapChecklists::create([
                            'id_parent_checklist' => $par->id,
                            'type_jaringan' => $type
                        ]);

                    }
                }
            }

            DB::commit();
            //Audit Log
            $this->auditLogsShort('Add Type Checklist Mst MapChecklist');
            return redirect()->back()->with(['success' => 'Success Add New Type Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add New Type Checklist!']);
        } 
    }
    public function deletetype($type)
    {

        $type = decrypt($type);
        $parent = MstParentChecklists::where('type_checklist', $type)->get();
        DB::beginTransaction();
        try{
            

            // menggunakan perulangan untuk delete data
            foreach ($parent as $par) {
                // echo $par['id'] . "<br>";
                MstMapChecklists::where('id_parent_checklist', $par['id'])->delete();
            }

            DB::commit();
            //Audit Log
            $this->auditLogsShort('Delete Type Checklist Mst MapChecklist');
            return redirect()->back()->with(['success' => 'Success Delete Type Checklist : '.$type]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Type Checklist! : '.$type]);
        }
        

        
    }
    public function detail($type, $typecheck)
    {
        $type = decrypt($type); // type jaringan
        $typecheck = decrypt($typecheck); // type checklist

        //disini kita ambil semua parent yang sesuai dengan type checklist

        $parent = MstParentChecklists::where('type_checklist', $typecheck)
        ->whereNotIn('mst_parent_checklists.id', function($query) use ($type) {
            $query->select('id_parent_checklist')
                ->from('mst_mapchecklists')
                ->where('type_jaringan', $type);
        })
        ->get();
        $datas = MstMapChecklists::join('mst_parent_checklists', 'mst_mapchecklists.id_parent_checklist', '=', 'mst_parent_checklists.id')
        ->orderBy('mst_parent_checklists.id', 'asc')
        ->where('type_checklist', $typecheck)->get();

        // dd($datas);

        //Audit Log
        $this->auditLogsShort('View List Parent Type Checklist Mst MapChecklist');

        return view('mapchecklist.parentcheck', compact('parent', 'datas', 'typecheck', 'type'));
    }
    public function addparent($type, Request $request)
    {
        $type = decrypt($type); // type jaringan
        DB::beginTransaction();
        try{
            

            // menggunakan perulangan untuk input data
            foreach ($request->id_parent as $id_parent) {
                // echo $id_parent . "<br>";
                MstMapChecklists::create([
                    'id_parent_checklist' => $id_parent,
                    'type_jaringan' => $type
                ]);
            }

            DB::commit();
            //Audit Log
            $this->auditLogsShort('Add Parent Checklist Mst MapChecklist');
            return redirect()->back()->with(['success' => 'Success Add Parent Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add Parent Checklist!']);
        } 
    }
    public function deleteparent($id)
    {

        $id = decrypt($id);
        DB::beginTransaction();
        try{
            
            // menggunakan perulangan untuk delete data
            MstMapChecklists::where('id_parent_checklist', $id)->delete();

            DB::commit();
            //Audit Log
            $this->auditLogsShort('Delete Parent Mst MapChecklist');
            return redirect()->back()->with(['success' => 'Success Delete Parent Checklist']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Parent Checklist!']);
        }
        
    }
}

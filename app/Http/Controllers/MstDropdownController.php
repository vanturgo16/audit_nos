<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstDropdowns;

class MstDropdownController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $datas=MstDropdowns::get();

        $category = MstDropdowns::select('category')->get();
        $category = $category->unique('category');

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Dropdown';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('dropdown.index',compact('datas', 'category'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);

        if($request->category == "NewCat"){
            $category = $request->addcategory;
        }
        else{
            $category = $request->category;
        }

        DB::beginTransaction();
        try{
            
            MstDropdowns::create([
                'category' => $category,
                'name_value' => $request->name_value,
                'code_format' => $request->code_format,
                'is_active' => '1'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Dropdown';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Dropdown']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Dropdown!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);

        if($request->category == "NewCat"){
            $category = $request->addcategory;
        }
        else{
            $category = $request->category;
        }

        $databefore = MstDropdowns::where('id', $id)->first();
        $databefore->category = $category;
        $databefore->name_value = $request->name_value;
        $databefore->code_format = $request->code_format;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstDropdowns::where('id', $id)->update([
                    'category' => $category,
                    'name_value' => $request->name_value,
                    'code_format' => $request->code_format
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Dropdown';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Dropdown']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Dropdown!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstDropdowns::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstDropdowns::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Dropdown ('. $name->name_value . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Dropdown ' . $name->name_value]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Dropdown ' . $name->name_value .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstDropdowns::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstDropdowns::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Dropdown ('. $name->name_value . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Dropdown ' . $name->name_value]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Dropdown ' . $name->name_value .'!']);
        }
    }
}

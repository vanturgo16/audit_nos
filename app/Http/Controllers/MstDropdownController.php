<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\MstDropdowns;

class MstDropdownController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $category = MstDropdowns::select('category')->get();
        $category = $category->unique('category');
        
        if ($request->ajax()) {
            $data = $this->getData($category);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Dropdown');
        
        return view('dropdown.index',compact('category'));
    }

    private function getData($category)
    {
        $query=MstDropdowns::orderBy('category')->get();

        $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($category){
                return view('dropdown.action', compact('data', 'category'));
            })
            ->toJson();

        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(),[
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

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
            $this->auditLogsShort('Create New Dropdown');

            DB::commit();
            
            return redirect()->back()->with(['success' => 'Success Create New Dropdown']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Dropdown!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);
        $validate = Validator::make($request->all(),[
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

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
                $this->auditLogsShort('Update Dropdown');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Update Dropdown']);
            } catch (Exception $e) {
                DB::rollback();
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
            $this->auditLogsShort('Activate Dropdown ('. $name->name_value . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate ' . $name->name_value]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate ' . $name->name_value .'!']);
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
            $this->auditLogsShort('Deactivate Dropdown ('. $name->name_value . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate ' . $name->name_value]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate ' . $name->name_value .'!']);
        }
    }
}

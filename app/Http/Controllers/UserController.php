<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

// Model
use App\Models\User;
use App\Models\MstDropdowns;
use App\Models\MstEmployees;

class UserController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $role = MstDropdowns::where('category', 'Role User')->get();

        if ($request->ajax()) {
            $data = $this->getData($role);
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst User');
        
        return view('users.index', compact('role'));
    }

    private function getData($role)
    {
        $query = User::orderBy('created_at')->get();
        $data = DataTables::of($query)
            ->addColumn('action', function ($data) use ($role){
                return view('users.action', compact('data', 'role'));
            })
            ->toJson();

        return $data;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validate = Validator::make($request->all(),[
            'email' => 'required',
            'role' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $count= MstEmployees::where('email',$request->email)->count();
        if($count < 1){
            return redirect()->back()->with('warning','Email Have Not Registered As Employee');
        } else {
            $name= MstEmployees::where('email',$request->email)->first()->employee_name;
        }

        $count= User::where('email',$request->email)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Email Was Already Registered As User');
        } else {
            DB::beginTransaction();
            try{
                $users = User::create([
                    'name' => $name,
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'is_active' => '1',
                    'role' => $request->role
                ]);

                //Audit Log
                $this->auditLogsShort('Create New User ('. $request->email . ')');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Create New User']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New User!']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $iduser = decrypt($id);

        $validate = Validator::make($request->all(),[
            'role' => 'required',
        ]);
        if($validate->fails()){
            return redirect()->back()->withInput()->with(['fail' => 'Failed, Check Your Input']);
        }

        $userbefore = User::where('id', $iduser)->first();
        $userbefore->role = $request->role;

        if($userbefore->isDirty()){
            DB::beginTransaction();
            try{
                $users = User::where('id', $iduser)->update([
                    'role' => $request->role
                ]);

                //Audit Log
                $this->auditLogsShort('Update User ('. $userbefore->email . ')');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update User']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update User!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id)
    {
        $iduser = decrypt($id);
        // dd($iduser);

        DB::beginTransaction();
        try{
            $users = User::where('id', $iduser)->delete();

            $name = User::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Delete User ('. $name->email . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete User ' . $name->email .'!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = User::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate User ('. $name->email . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate User ' . $name->email .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = User::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate User ('. $name->email . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate User ' . $name->email .'!']);
        }
    }

    public function check_email(Request $request)
    {
        $email = $request->input('email');
        $isEmailRegist = MstEmployees::where('email', $email)->first();
        if ($isEmailRegist || $email == null) {
            return response()->json(['status' => 'registered']);
        } else {
            return response()->json(['status' => 'notregistered']);
        }
    }
}

<?php

namespace App\Http\Controllers;


use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiRegionalTrait;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\MstEmployees;
use App\Models\MstJaringan;
use App\Models\MstDepartments;

class MstEmployeeController extends Controller
{
    use AuditLogsTrait;
    use ApiRegionalTrait;

    public function index(Request $request)
    {
        // API
        $tokenregional = $this->getTokenRegional();
        $provinces = $this->getProvinceRegional($tokenregional);

        $datas = MstEmployees::join('mst_dealers', 'mst_employees.id_dealer', '=', 'mst_dealers.id')
        ->join('mst_departments', 'mst_employees.id_dept', '=', 'mst_departments.id')
        ->join('mst_positions', 'mst_employees.id_position', '=', 'mst_positions.id')
        ->select('mst_employees.*', 'mst_dealers.dealer_name', 'mst_departments.department_name', 'mst_positions.position_name')
        ->get();
        $dealer=MstJaringan::get();
        $department=MstDepartments::get();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) use ($provinces, $dealer, $department) {
                return view('employee.action', compact('data', 'provinces', 'dealer', 'department'));
            })
            ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Employee');
        
        return view('employee.index',compact('datas', 'dealer', 'department', 'provinces'));
        // dd($datas);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_dealer' => 'required',
            'id_dept' => 'required',
            'id_position' => 'required',
            'email' => 'required',
            'employee_name' => 'required',
            'employee_nik' => 'required',
            'employee_telephone' => 'required',
            'employee_address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'zipcode' => 'required'
        ]);

        DB::beginTransaction();
        try{
            
            MstEmployees::create([
                'id_dealer' => $request->id_dealer,
                'id_dept' => $request->id_dept,
                'id_position' => $request->id_position,
                'email' => $request->email,
                'employee_name' => $request->employee_name,
                'employee_nik' => $request->employee_nik,
                'employee_telephone' => $request->employee_telephone,
                'employee_address' => $request->employee_address,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'subdistrict' => $request->subdistrict,
                'postal_code' => $request->zipcode
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Employee');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Employee']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Create New Employee!']);
        }
    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $request->validate([
            'id_dealer' => 'required',
            'id_dept' => 'required',
            'id_position' => 'required',
            'email' => 'required',
            'employee_name' => 'required',
            'employee_nik' => 'required',
            'employee_telephone' => 'required',
            'employee_address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'zipcode' => 'required'
        ]);


        $databefore = MstEmployees::where('id', $id)->first();
        $databefore->id_dealer = $request->id_dealer;
        $databefore->id_dept = $request->id_dept;
        $databefore->id_position = $request->id_position;
        $databefore->employee_name = $request->employee_name;
        $databefore->employee_nik = $request->employee_nik;
        $databefore->employee_telephone = $request->employee_telephone;
        $databefore->employee_address = $request->employee_address;
        $databefore->province = $request->province;
        $databefore->city = $request->city;
        $databefore->district = $request->district;
        $databefore->subdistrict = $request->subdistrict;
        $databefore->postal_code = $request->zipcode;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstEmployees::where('id', $id)->update([
                    'id_dealer' => $request->id_dealer,
                    'id_dept' => $request->id_dept,
                    'id_position' => $request->id_position,
                    'employee_name' => $request->employee_name,
                    'employee_nik' => $request->employee_nik,
                    'employee_telephone' => $request->employee_telephone,
                    'employee_address' => $request->employee_address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'district' => $request->district,
                    'subdistrict' => $request->subdistrict,
                    'postal_code' => $request->zipcode
                ]);

                //Audit Log
                $this->auditLogsShort('Update Employee');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Employee']);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update Employee!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function check_email(Request $request)
    {
        $email = $request->input('email');
        $idList = $request->idList;

        if($idList != null){
            $isEmailUsed = MstEmployees::where('id', '!=', $idList)->where('email', $email)->first();
        } else {
            $isEmailUsed = MstEmployees::where('email', $email)->first();
        }
        
        if ($isEmailUsed) {
            return response()->json(['status' => 'used']);
        } else {
            return response()->json(['status' => 'available']);
        }
    }
}

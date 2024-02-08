<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiRegionalTrait;
use Browser;

// Model
use App\Models\MstBranch;
use App\Models\MstDropdowns;

class MstBranchController extends Controller
{
    use AuditLogsTrait;
    use ApiRegionalTrait;

    public function index()
    {
        // API
        $tokenregional = $this->getTokenRegional();
        $provinces = $this->getProvinceRegional($tokenregional);

        $datas=MstBranch::get();
        $type_dealer = MstDropdowns ::where('category', 'Type Dealer')->get();

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Dealer';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('dealer.index',compact('datas','type_dealer', 'provinces'));
        // $columns = DB::table('mst_dealers')->get()->first();
        // dd(array_keys((array) $columns));
    }
    public function store(Request $request)
    {

        $request->validate([
            'type' => 'required',
            'dealer_name' => 'required',
            'dealer_code' => 'required',
            'dealer_address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'zipcode' => 'required'
        ]);

        DB::beginTransaction();
        try{
            
            MstBranch::create([
                'type' => $request->type,
                'dealer_name' => $request->dealer_name,
                'dealer_code' => $request->dealer_code,
                'dealer_address' => $request->dealer_address,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'subdistrict' => $request->subdistrict,
                'postal_code' => $request->zipcode
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Dealer';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Dealer']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Dealer!']);
        }
    }
    public function update(Request $request, $id)
    {

        $id = decrypt($id);

        $request->validate([
            'type' => 'required',
            'dealer_name' => 'required',
            'dealer_code' => 'required',
            'dealer_address' => 'required',
            // 'province' => 'required',
            // 'city' => 'required',
            // 'district' => 'required',
            // 'subdistrict' => 'required',
            // 'zipcode' => 'required'
        ]);

        $databefore = MstBranch::where('id', $id)->first();
        $databefore->type = $request->type;
        $databefore->dealer_name = $request->dealer_name;
        $databefore->dealer_code = $request->dealer_code;
        $databefore->dealer_address = $request->dealer_address;
        $databefore->province = $request->province;
        $databefore->city = $request->city;
        $databefore->district = $request->district;
        $databefore->subdistrict = $request->subdistrict;
        $databefore->postal_code = $request->zipcode;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstBranch::where('id', $id)->update([
                    'type' => $request->type,
                    'dealer_name' => $request->dealer_name,
                    'dealer_code' => $request->dealer_code,
                    'dealer_address' => $request->dealer_address,
                    'province' => $request->province,
                    'city' => $request->city,
                    'district' => $request->district,
                    'subdistrict' => $request->subdistrict,
                    'postal_code' => $request->zipcode
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Dealer';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Dealer']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Dealer!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiRegionalTrait;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\MstJaringan;
use App\Models\MstDropdowns;

class MstJaringanController extends Controller
{
    use AuditLogsTrait;
    use ApiRegionalTrait;

    public function index(Request $request)
    {
        // API
        $tokenregional = $this->getTokenRegional();
        $provinces = $this->getProvinceRegional($tokenregional);

        $datas=MstJaringan::get();
        $type_dealer = MstDropdowns ::where('category', 'Type Dealer')->get();

        if ($request->ajax()) {
            $data = DataTables::of($datas)
            ->addColumn('action', function ($data) use ($provinces, $type_dealer) {
                return view('dealer.action', compact('data', 'provinces', 'type_dealer'));
            })
            ->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Dealer / Jaringan');
        
        return view('dealer.index',compact('datas','type_dealer', 'provinces'));
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
            
            MstJaringan::create([
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
            $this->auditLogsShort('Create New Dealer / Jaringan');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Dealer / Jaringan']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Create New Dealer / Jaringan!']);
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

        $databefore = MstJaringan::where('id', $id)->first();
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
                MstJaringan::where('id', $id)->update([
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
                $this->auditLogsShort('Update Dealer / Jaringan');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Dealer / Jaringan']);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update Dealer / Jaringan!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstDealers;

class MstDealerController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $datas=MstDealers::get();

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Dealer';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('dealer.index',compact('datas'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'dealer_name' => 'required',
            'dealer_address' => 'required',
        ]);

        DB::beginTransaction();
        try{
            
            MstDealers::create([
                'dealer_name' => $request->dealer_name,
                'dealer_address' => $request->dealer_address
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
            'dealer_name' => 'required',
            'dealer_address' => 'required',
        ]);

        $databefore = MstDealers::where('id', $id)->first();
        $databefore->dealer_name = $request->dealer_name;
        $databefore->dealer_address = $request->dealer_address;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstDealers::where('id', $id)->update([
                    'dealer_name' => $request->dealer_name,
                    'dealer_address' => $request->dealer_address
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

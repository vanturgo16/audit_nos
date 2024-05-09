<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Model
use App\Models\MstGrading;

class MstGradingController extends Controller
{
    use AuditLogsTrait;
    public function index(Request $request)
    {
        $datas = MstGrading::get();

        if ($request->ajax()) {
            $data = DataTables::of($datas)->toJson();
            return $data;
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Grading');
        
        return view('grading.index',compact('datas'));
    }
}

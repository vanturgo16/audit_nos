<?php

namespace App\Http\Controllers;

use App\Models\MstGrading;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;

class MstGradingController extends Controller
{
    use AuditLogsTrait;
    public function index()
    {

        $datas=MstGrading::get();

        //Audit Log
        $this->auditLogsShort('View List Mst Grading');
        return view('grading.index',compact('datas'));

    }
}

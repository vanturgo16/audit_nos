<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getData();
            return $data;
        }
        
        //Audit Log
        $this->auditLogsShort('View List Audit Log');

        return view('auditlog.index');
    }

    private function getData()
    {
        $query = AuditLog::orderBy('created_at')->get();
        $data = DataTables::of($query)->toJson();
        return $data;
    }
}

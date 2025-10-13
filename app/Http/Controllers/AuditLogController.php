<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\AuditLog;
use App\Models\LogActivityPeriod;
use App\Models\MstPeriodeChecklists;

class AuditLogController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        if (!$request->has('monthYear') || $request->monthYear == "") {
            $monthYear = Carbon::now()->format('Y-m');
        } else {
            $monthYear = $request->monthYear;
        }
        [$year, $month] = explode('-', $monthYear);
        $datas = AuditLog::select('audit_logs.*', 'created_at as created')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($datas)->toJson();
        }

        // Audit Log
        $this->auditLogsShort('View List Audit Log');
        return view('auditlog.index', compact('monthYear'));
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = $this->getData();
    //         return $data;
    //     }

    //     //Audit Log
    //     $this->auditLogsShort('View List Audit Log');

    //     return view('auditlog.index');
    // }

    // private function getData()
    // {
    //     $query = AuditLog::orderBy('created_at')->get();
    //     $data = DataTables::of($query)->toJson();
    //     return $data;
    // }

    public function logActivityPeriod(Request $request, $id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::where('id', $id)->first();
        $datas = LogActivityPeriod::where('id_period', $id)->orderby('created_at', 'desc')->get();

        //Audit Log
        $this->auditLogsShort('View List Log Activity Period ' . $id);

        return view('auditlog.activitylog-period', compact('periodInfo', 'datas'));
    }
}

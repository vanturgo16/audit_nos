<?php

namespace App\Http\Controllers;

use App\Exports\PeriodExport;
use App\Traits\AuditLogsTrait;
use Maatwebsite\Excel\Facades\Excel;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\ChecklistJaringan;
use App\Models\MstAssignChecklists;

class ExportController extends Controller
{
    use AuditLogsTrait;

    public function exportPeriod($id)
    {
        $id = decrypt($id);
        $periodInfo = MstPeriodeChecklists::leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('mst_periode_checklists.id', $id)
            ->first();
        $dataCheck = MstAssignChecklists::select('mst_assign_checklists.*', 'checklist_responses.response', 'checklist_responses.response_correction', 'checklist_responses.path_input_response')
            ->leftJoin('checklist_responses', 'mst_assign_checklists.id', 'checklist_responses.id_assign_checklist')
            ->where('mst_assign_checklists.id_periode_checklist', $id)
            ->orderby('mst_assign_checklists.order_no_parent')
            ->orderby('mst_assign_checklists.order_no_checklist')
            ->get()
            ->groupBy('type_checklist');
        $checkJar = ChecklistJaringan::where('id_periode', $id)->get();
        foreach ($checkJar as $item) {
            if (isset($dataCheck[$item->type_checklist])) {
                $dataCheck[$item->type_checklist]->push($item);
            } else {
                $dataCheck[$item->type_checklist] = collect([$item]);
            }
        }
        // dd($periodInfo, $dataCheck);

        //Audit Log
        $this->auditLogsShort('Export Period ID = ' . $id);

        $fileName = 'Period_' . $periodInfo->period . '_' . $periodInfo->dealer_name . '_' . $periodInfo->type . '_' . date('YmdHis') . '.xlsx';
        return Excel::download(new PeriodExport($dataCheck), $fileName);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ChecklistJaringan;
use App\Models\ChecklistResponses;
use App\Models\MstAssignChecklists;
use App\Models\MstDropdowns;
use Illuminate\Http\Request;

//Model
use App\Models\MstJaringan;
use App\Models\MstEmployees;
use App\Models\MstPeriodeChecklists;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $roleUser = auth()->user()->role;

        if (in_array($roleUser, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC NOS MD', 'PIC Dealers'])) {
            $dealers = MstJaringan::select('id', 'dealer_name')->get();
            $periods = [];
        } else {
            $emailUser = auth()->user()->email;
            $dealers = MstEmployees::where('email', $emailUser)->first()->id_dealer;
            $periods = MstPeriodeChecklists::select('id', 'period')->where('id_branch', $dealers)->get();
        }

        $dataGraph = [];
        if ($request->id_period) {
            $idPeriod = $request->id_period;
            $idDealer = MstPeriodeChecklists::where('id', $idPeriod)->first()->id_branch;
            $periodslist = MstPeriodeChecklists::select('id', 'period')->where('id_branch', $idDealer)->get();
            //Result
            $statusPeriod = MstPeriodeChecklists::where('id', $idPeriod)->first()->status;
            //ForSortingBasedDropdown
            $sortdropdown = MstDropdowns::where('category', 'Type Checklist')->orderby('created_at')->pluck('name_value')->toArray();
            $typechecklist = ChecklistJaringan::select('id as id_checklist_jaringan', 'type_checklist')->where('id_periode', $idPeriod)->orderByRaw("FIELD(type_checklist, '" . implode("','", $sortdropdown) . "')")->get();
            $typechecklistValues = $typechecklist->pluck('type_checklist')->toArray();

            $resultchecklist = ChecklistJaringan::select(
                'type_checklist',
                'result_percentage',
                'status',
                'audit_result',
                'mandatory_item',
                'result_final'
            )
                ->where('id_periode', $idPeriod)
                ->orderByRaw("FIELD(type_checklist, '" . implode("','", $typechecklistValues) . "')")
                ->get();
            if ($resultchecklist) {
                foreach ($resultchecklist as $result) {
                    //Formula % Graph
                    if ($result->mandatory_item == 'Platinum') {
                        $graph_percentage = 91;
                    } elseif ($result->mandatory_item == 'Gold') {
                        $graph_percentage = 71;
                    } elseif ($result->mandatory_item == 'Silver') {
                        $graph_percentage = 61;
                    } elseif ($result->mandatory_item == 'Bronze') {
                        $graph_percentage = 1;
                    } else {
                        $graph_percentage = 0;
                    }
                    $result->graph_percentage = $graph_percentage;
                }
                foreach ($resultchecklist as $result) {
                    $dataGraph[] = $result->graph_percentage;
                }
            }
        } else {
            $idPeriod = null;
            $idDealer = null;
            $periodslist = null;
            $statusPeriod = 'notselect';
            $typechecklistValues = null;
            $typechecklist = null;
            $resultchecklist = null;
        }

        // dd($dataGraph, $statusPeriod, $typechecklistValues, $typechecklist, $resultchecklist);

        return view('dashboard.index', compact(
            'dealers',
            'periods',
            'idPeriod',
            'idDealer',
            'periodslist',
            'statusPeriod',
            'typechecklist',
            'typechecklistValues',
            'resultchecklist',
            'dataGraph'
        ));
    }

    public function mappingdealer($idDealer)
    {
        // Get List Period
        $periods = MstPeriodeChecklists::select('id', 'period')->where('id_branch', $idDealer)->get();
        return $periods;
    }

    public function detailresult($idcheckjaringan)
    {
        $id = decrypt($idcheckjaringan);
        $checkjaringan = ChecklistJaringan::select('checklist_jaringan.id_periode', 'checklist_jaringan.type_checklist', 'mst_periode_checklists.period', 'mst_dealers.type', 'mst_dealers.dealer_name')
            ->leftjoin('mst_periode_checklists', 'checklist_jaringan.id_periode', 'mst_periode_checklists.id')
            ->leftjoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
            ->where('checklist_jaringan.id', $id)
            ->first();

        $data = MstAssignChecklists::select('parent_point_checklist')
            ->where('id_periode_checklist', $checkjaringan->id_periode)
            ->where('type_checklist', $checkjaringan->type_checklist)
            ->groupBy('parent_point_checklist')
            ->get();

        $countAllTotalChecked = 0;
        $countAllTotalCheckedEG = 0;
        $countAllTotalResultPercentage = 0;
        $countTotalParent = 0;
        foreach ($data as $item) {
            $idAssigns = MstAssignChecklists::where('id_periode_checklist', $checkjaringan->id_periode)
                ->where('type_checklist', $checkjaringan->type_checklist)
                ->where('parent_point_checklist', $item->parent_point_checklist)
                ->pluck('id')->toArray();
            $item->countTotalChecked = ChecklistResponses::whereIn('id_assign_checklist', $idAssigns)->count();
            $item->countTotalCheckedEG = ChecklistResponses::whereIn('id_assign_checklist', $idAssigns)->where('checklist_responses.response', 'Exist, Good')->count();
            $item->resultPercentage = intval($item->countTotalCheckedEG > 0 ? round(($item->countTotalCheckedEG / $item->countTotalChecked) * 100) : 0);

            // Update totals
            $countAllTotalChecked += $item->countTotalChecked;
            $countAllTotalCheckedEG += $item->countTotalCheckedEG;
            $countAllTotalResultPercentage += $item->resultPercentage;
            $countTotalParent++;
        }
        $avgTotalResultPercentage = intval(round($countAllTotalResultPercentage) / $countTotalParent);

        $typeparentValues = $data->pluck('parent_point_checklist')->toArray();
        $dataGraph = $data->pluck('resultPercentage')->toArray();

        return view('dashboard.detail', compact('checkjaringan', 'data', 'countAllTotalChecked', 'countAllTotalCheckedEG', 'avgTotalResultPercentage', 'typeparentValues', 'dataGraph'));
    }
}

<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\MstAssignChecklists;
use App\Models\MstRules;
use App\Models\User;

// Mail 
use App\Mail\AlertExpiredPeriod;

class AlertExpiredPeriodCommand extends Command
{
    protected $signature = 'AlertExpiredPeriodCommand';
    protected $description = 'Warning Expired Period Checklist';

    public function handle()
    {
        $today = Carbon::today();

        DB::beginTransaction();
        try{
            // Get Period EndDate Today
            $periodexpired = MstPeriodeChecklists::select('mst_periode_checklists.*')
                ->where('is_active', '1')
                ->where('status', '1')
                ->where('end_date', '<=', $today)
                ->get();

            foreach($periodexpired as $expired){
                // Update is_active Period to Expired (3)
                MstPeriodeChecklists::where('id', $expired->id)->update(['status' => null]);

                // Send Email Alert To Internal Auditor
                // [ MAILING ]
                // Initiate Variable
                $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
                    ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                    ->where('mst_periode_checklists.id', $expired->id)
                    ->first();
                $count = MstAssignChecklists::where('id_periode_checklist', $expired->id)->count();
                $periodinfo->count = $count;
                // Recepient Email
                if($development == 1){
                    $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                    $ccemail = null;
                } else {
                    // $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                    $toemail = MstPeriodeChecklists::leftJoin('mst_employees', 'mst_periode_checklists.id_branch', 'mst_employees.id_dealer')
                        ->leftJoin('users', 'mst_employees.email', 'users.email')
                        ->where('mst_periode_checklists.id', $expired->id)
                        ->where('users.role', 'Internal Auditor Dealer')
                        ->pluck('mst_employees.email')->toArray();
                    $ccemail = User::where('role', 'PIC Dealers')->pluck('email')->toArray();
                }
                // Mail Content
                $mailInstance = new AlertExpiredPeriod($periodinfo);
                // Send Email
                Mail::to($toemail)->cc($ccemail)->send($mailInstance);
            }

            DB::commit();
            echo ('Success Running Command at '.$today);
        } catch (Exception $e) {
            DB::rollback();
            echo ('Failed Run Command at '.$today.' error: '.$e);
        }
    }
}

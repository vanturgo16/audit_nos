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
use App\Mail\ReminderSubmitPeriod;

class ReminderSubmitPeriodCommand extends Command
{
    protected $signature = 'ReminderSubmitPeriodCommand';
    protected $description = 'Reminder Submit Period Checklist';

    public function handle()
    {
        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');

        DB::beginTransaction();
        try{
            // Get Period StartDate Today
            $remindsubmit = MstPeriodeChecklists::select('mst_periode_checklists.*')
                ->where('is_active', '0')
                ->where('start_date', $formattedDate)
                ->get();

            foreach($remindsubmit as $submit){
                // Send Email Reminder Submit To Assessor
                // [ MAILING ]
                // Initiate Variable
                $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                $periodinfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type')
                    ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', 'mst_dealers.id')
                    ->where('mst_periode_checklists.id', $submit->id)
                    ->first();
                $count = MstAssignChecklists::where('id_periode_checklist', $submit->id)->count();
                $periodinfo->count = $count;
                // Recepient Email
                if($development == 1){
                    $toemail = MstRules::where('rule_name', 'Email Development')->first()->rule_value;
                } else {
                    $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                }
                // Mail Content
                $mailInstance = new ReminderSubmitPeriod($periodinfo);
                // Send Email
                Mail::to($toemail)->send($mailInstance);
            }

            DB::commit();
            echo ('Success Running Command at '.$today);
        } catch (Exception $e) {
            DB::rollback();
            echo ('Failed Run Command at '.$today.' error: '.$e);
        }
    }
}

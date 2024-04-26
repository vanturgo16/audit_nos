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
use App\Mail\ReminderExpiredPeriod;

class PeriodAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:period-audit-command';
    protected $signature = 'period:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Period Checklist';

    /**
     * Execute the console command.
     */
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

                // Send Email Reminder To Assessor
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
                    $toemail = MstRules::where('rule_name', 'Email Development')->first()->rule_value;
                } else {
                    $toemail = User::where('role', 'Assessor Main Dealer')->pluck('email')->toArray();
                }
                // Mail Content
                $mailInstance = new ReminderExpiredPeriod($periodinfo);
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

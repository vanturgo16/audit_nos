<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

// Model
use App\Models\MstPeriodeChecklists;
use App\Models\User;
use App\Models\LogActivityPeriod;

// Mail 
use App\Mail\AlertExpiredPeriod;

// Trait
use App\Traits\MailingTrait;

class AlertExpiredPeriodCommand extends Command
{
    use MailingTrait;

    protected $signature = 'AlertExpiredPeriodCommand';
    protected $description = 'Warning Expired Period Checklist';

    public function handle()
    {
        $today = Carbon::today();

        // Get Period EndDate Less Than Or Equal Today Where Status Still Audit/Revisi
        $periodExpired = MstPeriodeChecklists::where('end_date', '<=', $today)
            ->where('is_active', 1)->whereIn('status', [1, 2])->get();

        foreach ($periodExpired as $item) {
            // Variable
            $variableEmail = $this->variableEmail();
            $periodInfo = MstPeriodeChecklists::select('mst_periode_checklists.*', 'mst_dealers.dealer_name', 'mst_dealers.type', DB::raw('(SELECT COUNT(*) FROM mst_assign_checklists WHERE mst_assign_checklists.id_periode_checklist = mst_periode_checklists.id) as totalChecklist'))
                ->leftJoin('mst_dealers', 'mst_periode_checklists.id_branch', '=', 'mst_dealers.id')
                ->where('mst_periode_checklists.id', $item->id)
                ->first();
            // Recepient Email
            if ($variableEmail['devRule'] == 1) {
                $toemail = $variableEmail['emailDev'];
            } else {
                $toemail = User::leftjoin('mst_employees', 'users.email', 'mst_employees.email')
                    ->where('users.role', 'Internal Auditor Dealer')->where('mst_employees.id_dealer', $item->id_branch)
                    ->pluck('users.email')->toArray();
            }
            // Mail Structure
            $mailStructure = new AlertExpiredPeriod($periodInfo);

            DB::beginTransaction();
            try {
                // Update To Expired
                MstPeriodeChecklists::where('id', $item->id)->update(['is_active' => 0]);
                // Store Log
                LogActivityPeriod::create([
                    'id_period' => $item->id, 'status' => 8,
                    'note' => 'Period Ended', 'activity_by' => 'Scheduler By System',
                ]);
                // Send Email
                Mail::to($toemail)->send($mailStructure);

                DB::commit();
                echo ('Success Running Command at ' . $today);
            } catch (Exception $e) {
                DB::rollback();
                echo ('Failed Run Command at ' . $today . ' error: ' . $e);
            }
        }
    }
}

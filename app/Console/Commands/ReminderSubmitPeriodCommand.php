<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

// Model
use App\Models\MstPeriodeChecklists;

// Mail 
use App\Mail\ReminderSubmitPeriod;

// Trait
use App\Traits\MailingTrait;

class ReminderSubmitPeriodCommand extends Command
{
    use MailingTrait;

    protected $signature = 'ReminderSubmitPeriodCommand';
    protected $description = 'Reminder Submit Period Checklist';

    public function handle()
    {
        $today = Carbon::today();
        $formattedDate = $today->format('Y-m-d');

        DB::beginTransaction();
        try {
            // Get Period StartDate More Than Or Equal Today Where Status Still Initiate
            $periodStart = MstPeriodeChecklists::where('start_date', '>=', $formattedDate)
                ->where('status', 0)->get();

            foreach ($periodStart as $item) {
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
                    $toemail = $periodInfo->created_by;
                }
                // Mail Structure
                $mailStructure = new ReminderSubmitPeriod($periodInfo);
                // Send Email
                Mail::to($toemail)->send($mailStructure);
            }

            DB::commit();
            echo ('Success Running Command at ' . $today);
        } catch (Exception $e) {
            DB::rollback();
            echo ('Failed Run Command at ' . $today . ' error: ' . $e);
        }
    }
}

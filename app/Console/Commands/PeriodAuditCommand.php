<?php

namespace App\Console\Commands;

use App\Models\MstPeriodeChecklists;
use Carbon\Carbon;
use Illuminate\Console\Command;

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

        $period = MstPeriodeChecklists::where('start_date', $today)->where('is_active', '0')->get();

        foreach($period as $per){
            $per->update(
                [
                    'is_active' => '1'
                ]
            );
        }

    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradingResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mst_grading')->insert([
            ['bottom' => 0.00, 'top' => 59.9, 'result' => 'Bronze'],
            ['bottom' => 60.00, 'top' => 69.9, 'result' => 'Silver'],
            ['bottom' => 70.00, 'top' => 89.9, 'result' => 'Gold'],
            ['bottom' => 90.00, 'top' => 100.0, 'result' => 'Platinum'],
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('checklist_jaringan', function (Blueprint $table) {
            $table->integer('total_point')->nullable(); // didapat jadi jumlah exist *(1) berapa, not exist *(mines -1) berapa, dst
            $table->float('result_percentage')->nullable();//total point/ total checklist 
            $table->string('audit_result')->nullable();//kalkulasi dengan grading
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_jaringan', function (Blueprint $table) {
            $table->dropColumn('total_point');
            $table->dropColumn('result_percentage');
            $table->dropColumn('audit_result');
        });
    }
};

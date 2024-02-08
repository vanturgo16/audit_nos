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
        Schema::table('mst_employees', function (Blueprint $table) {
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_employees', function (Blueprint $table) {
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('district');
            $table->dropColumn('subdistrict');
        });
    }
};

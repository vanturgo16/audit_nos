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
        Schema::table('mst_periode_checklists', function (Blueprint $table) {
            $table->string('last_submit_audit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_periode_checklists', function (Blueprint $table) {
            $table->dropColumn('last_submit_audit');
        });
    }
};

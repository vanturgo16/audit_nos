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
            $table->string('decisionpic', 10)->nullable();
            $table->text('notespic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_periode_checklists', function (Blueprint $table) {
            $table->dropColumn('decisionpic');
            $table->dropColumn('notespic');
        });
    }
};

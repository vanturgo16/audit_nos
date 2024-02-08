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
            $table->string('email')->unique();
            $table->unsignedBigInteger('id_dept')->nullable();
            $table->unsignedBigInteger('id_position')->nullable();

    
            $table->foreign('id_dept')
                ->references('id')
                ->on('mst_departments')
                ->onDelete('cascade')
                ->onUpdate('cascade'); 

            $table->foreign('id_position')
                ->references('id')
                ->on('mst_positions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_employees', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropForeign(['id_dept']);
            $table->dropColumn('id_dept');
            $table->dropForeign(['position']);
            $table->dropColumn('position');
        });
    }
};

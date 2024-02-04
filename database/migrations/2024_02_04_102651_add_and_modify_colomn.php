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
        Schema::table('mst_checklists', function (Blueprint $table) {
            // tambah kolom setelah kolom id
            $table->unsignedBigInteger('id_parent_checklist')->nullable()->after('id'); 
            // hapus kolom type_checklist
            $table->dropColumn('type_checklist');
            // ganti nama point ke child
            $table->renameColumn('point_checklist', 'child_point_checklist');

            $table->foreign('id_parent_checklist')
            ->references('id')
            ->on('mst_parent_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_checklists', function (Blueprint $table) {
            //
        });
    }
};

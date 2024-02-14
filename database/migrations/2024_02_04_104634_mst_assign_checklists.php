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
        Schema::create('mst_assign_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periode_checklist');
            $table->unsignedBigInteger('id_parent_checklist');
            $table->timestamps();

            $table->foreign('id_periode_checklist')
            ->references('id')
            ->on('mst_periode_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');

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
        Schema::dropIfExists('mst_assign_checklists');
    }
};

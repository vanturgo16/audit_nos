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
        Schema::create('checklist_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_assign_checklist');
            $table->string('response');
            $table->timestamps();

            $table->foreign('id_assign_checklist')
            ->references('id')
            ->on('mst_assign_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_response');
    }
};

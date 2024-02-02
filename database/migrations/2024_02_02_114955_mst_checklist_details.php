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
        Schema::create('mst_checklist_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_checklist');
            $table->string('result');
            $table->string('meta_name');
            $table->string('meta_value', 1);
            $table->timestamps();

            $table->foreign('id_checklist')
            ->references('id')
            ->on('mst_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_checklists');
    }
};

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
        // ini kalau assign sudah di submit, 
        // dia dapet type by group dari parent
        Schema::create('checklist_jaringan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periode');
            $table->string('type_checklist');
            $table->integer('total_checklist')->nullable();
            $table->integer('checklist_remaining')->nullable();
            $table->integer('status')->nullable();
            $table->datetime('start_date')->nullable();
            $table->timestamps();

            $table->foreign('id_periode')
            ->references('id')
            ->on('mst_periode_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_jaringan');
    }
};

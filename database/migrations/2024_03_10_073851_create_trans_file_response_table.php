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
        Schema::create('trans_file_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_period');
            $table->unsignedBigInteger('id_parent');
            $table->timestamps();

            $table->foreign('id_period')
            ->references('id')
            ->on('mst_periode_checklists')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('id_parent')
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
        Schema::dropIfExists('trans_file_response');
    }
};

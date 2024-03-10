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
        Schema::create('file_input_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trans_file');
            $table->string('path_url');
            $table->timestamps();

            $table->foreign('id_trans_file')
            ->references('id')
            ->on('trans_file_response')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_input_response');
    }
};

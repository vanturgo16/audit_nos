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
        Schema::create('mst_mapchecklists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_parent_checklist')->unsigned();
            $table->string('type_jaringan', 100);
            $table->timestamps();

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
        Schema::dropIfExists('mst_mapchecklists');
    }
};

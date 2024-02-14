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
        Schema::create('mst_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('type_checklist');
            $table->string('point_checklist');
            $table->string('sub_point_checklist');
            $table->text('indikator');
            $table->string('mandatory_silver' , 1);
            $table->string('mandatory_gold' , 1);
            $table->string('mandatory_platinum' , 1);
            $table->string('upload_file' , 1);
            $table->timestamps();
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

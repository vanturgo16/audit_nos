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
        Schema::create('mst_parent_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('type_checklist');
            $table->string('parent_point_checklist');
            $table->string('path_guide_premises');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_parent_checklists');
    }
};

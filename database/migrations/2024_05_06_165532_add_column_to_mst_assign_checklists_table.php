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
        Schema::table('mst_assign_checklists', function (Blueprint $table) {
            $table->string('type_checklist')->nullable();
            $table->string('parent_point_checklist')->nullable();
            $table->string('path_guide_parent')->nullable();
            $table->string('child_point_checklist')->nullable();
            $table->string('sub_point_checklist')->nullable();
            $table->text('indikator')->nullable();
            $table->string('ms', 1)->nullable();
            $table->string('mg', 1)->nullable();
            $table->string('mp', 1)->nullable();
            $table->string('upload_file', 1)->nullable();
            $table->string('path_guide_checklist')->nullable();
            $table->string('mark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mst_assign_checklists', function (Blueprint $table) {
            $table->dropColumn('type_checklist');
            $table->dropColumn('parent_point_checklist');
            $table->dropColumn('path_guide_parent');
            $table->dropColumn('child_point_checklist');
            $table->dropColumn('sub_point_checklist');
            $table->dropColumn('indikator');
            $table->dropColumn('ms');
            $table->dropColumn('mg');
            $table->dropColumn('mp');
            $table->dropColumn('upload_file');
            $table->dropColumn('path_guide_checklist');
            $table->dropColumn('mark');
        });
    }
};

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
        Schema::create('mst_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dealer');
            $table->string('employee_name');
            $table->string('employee_nik');
            $table->string('employee_telephone');
            $table->string('employee_address');
            $table->timestamps();

            $table->foreign('id_dealer')
            ->references('id')
            ->on('mst_dealers')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_employees');
    }
};

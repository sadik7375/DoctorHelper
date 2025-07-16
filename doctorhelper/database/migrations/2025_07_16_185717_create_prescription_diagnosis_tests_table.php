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
    Schema::create('prescription_diagnosis_tests', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('prescription_id');
        $table->unsignedBigInteger('diagnosis_test_id');
        $table->timestamps();

        $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        $table->foreign('diagnosis_test_id')->references('id')->on('diagnosis_tests')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_diagnosis_tests');
    }
};

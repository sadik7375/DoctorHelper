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
    Schema::create('prescription_clinical_diagnoses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('prescription_id');
        $table->unsignedBigInteger('clinical_diagnosis_id');
        $table->timestamps();

        $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        $table->foreign('clinical_diagnosis_id')->references('id')->on('clinical_diagnoses')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_clinical_diagnoses');
    }
};

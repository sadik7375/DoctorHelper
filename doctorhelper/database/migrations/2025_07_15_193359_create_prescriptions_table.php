<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/xxxx_xx_xx_create_prescriptions_table.php
public function up()
{
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

        // Drug Variation
        $table->foreignId('drug_id')->constrained('drugs')->onDelete('cascade');
        $table->foreignId('drug_strength_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('drug_dose_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('drug_duration_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('drug_advice_id')->nullable()->constrained()->onDelete('set null');

        // Diagnosis
        $table->foreignId('clinical_diagnosis_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('diagnosis_test_id')->nullable()->constrained()->onDelete('set null');

        // Advice + Notes
        $table->text('advice')->nullable();

        // Next Follow Up
        $table->integer('follow_up_value')->nullable(); // e.g., 6
        $table->enum('follow_up_unit', ['days', 'weeks', 'months'])->nullable(); // e.g., Months
        $table->date('next_follow_up')->nullable(); // final calculated date

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};

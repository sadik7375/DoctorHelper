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
    Schema::create('prescription_drugs', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('prescription_id');
        $table->unsignedBigInteger('drug_id');
        $table->unsignedBigInteger('drug_dose_id')->nullable();
        $table->unsignedBigInteger('drug_strength_id')->nullable();
        $table->unsignedBigInteger('drug_duration_id')->nullable();
        $table->unsignedBigInteger('advice_id')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();

        $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('cascade');
        $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
        $table->foreign('drug_dose_id')->references('id')->on('drug_doses');
        $table->foreign('drug_strength_id')->references('id')->on('drug_strengths');
        $table->foreign('drug_duration_id')->references('id')->on('drug_durations');
        $table->foreign('advice_id')->references('id')->on('drug_advices');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_drugs');
    }
};

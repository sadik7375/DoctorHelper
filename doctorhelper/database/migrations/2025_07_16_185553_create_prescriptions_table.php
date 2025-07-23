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
    Schema::create('prescriptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('doctor_id');
        $table->unsignedBigInteger('patient_id');

        // Changed from relation to plain text
        $table->text('advice')->nullable(); // Changed from 'drug_advice_id'

        $table->unsignedInteger('next_follow_up_count')->nullable();
        $table->enum('next_follow_up_unit', ['days', 'weeks', 'months', 'years'])->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
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

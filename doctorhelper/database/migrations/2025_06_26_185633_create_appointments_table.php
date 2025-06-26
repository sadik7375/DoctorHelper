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
       Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('doctor_id');
    $table->unsignedBigInteger('patient_id');
    $table->unsignedBigInteger('staff_id')->nullable();
    $table->date('appointment_date');
    $table->time('appointment_time');
    $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
    $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

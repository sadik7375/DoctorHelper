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
       Schema::create('patients', function (Blueprint $table) {
    $table->id();
    $table->string('patient_uid')->unique(); // random patient ID
    $table->unsignedBigInteger('doctor_id');
    
    $table->string('name');
    $table->string('email')->nullable();
    $table->string('phone_number');
    $table->integer('age');
    $table->string('blood_group')->nullable();
    $table->float('weight')->nullable();
    $table->float('height')->nullable();
    $table->enum('gender', ['male', 'female', 'other']);
    $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
    $table->text('address')->nullable();

    $table->timestamps();

    $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

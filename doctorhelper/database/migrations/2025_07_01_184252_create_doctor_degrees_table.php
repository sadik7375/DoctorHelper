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
       Schema::create('doctor_degrees', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('doctor_id');
    $table->string('title');
    $table->text('description')->nullable();
    $table->timestamps();

    $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_degrees');
    }
};

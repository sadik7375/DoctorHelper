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
           Schema::create('drug_doses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id'); // SaaS: linked to the doctor
            $table->string('value'); // e.g., "1 tablet", "2 teaspoons"
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });
    

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_doses');
    }
};

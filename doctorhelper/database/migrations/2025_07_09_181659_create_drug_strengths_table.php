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
       Schema::create('drug_strengths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id'); // For SaaS: to isolate data per doctor
            $table->string('value'); // E.g., 500mg, 100mg
            $table->timestamps();

            // Optional: Add index & foreign key
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_strengths');
    }
};

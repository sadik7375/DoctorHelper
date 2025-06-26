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
       Schema::create('appointment_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('appointment_id');
    $table->decimal('fee', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->string('discount_reason')->nullable();
    $table->decimal('final_amount', 10, 2);
    $table->enum('payment_method', ['cash', 'card', 'bkash', 'rocket', 'nagad'])->nullable();
    $table->boolean('is_paid')->default(true);
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();

    $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_payments');
    }
};

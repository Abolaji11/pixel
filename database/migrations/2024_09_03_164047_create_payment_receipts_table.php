<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('payment_receipts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->unsignedBigInteger('job_id')->foreign('job_id')->references('id')->on('jobs')->onDelete('set null')->nullable();
        $table->string('payment_reference')->nullable();
        $table->string('status');
        $table->decimal('amount', 10, 2);
        $table->json('paystack_response')->nullable();
        $table->string('error_message')->nullable();
        $table->timestamps();

      
    });
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};




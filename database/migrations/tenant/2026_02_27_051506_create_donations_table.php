<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->index(); 
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('donation_type')->default('one_time'); 
            $table->string('stripe_payment_id')->nullable();
            $table->string('status')->default('completed'); 
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

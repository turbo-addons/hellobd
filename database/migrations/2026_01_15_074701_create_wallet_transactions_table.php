<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']); // credit = add money, debit = spend
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('description');
            $table->string('payment_method')->nullable(); // sslcommerz, bkash, nagad, card, manual
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->foreignId('advertisement_id')->nullable()->constrained()->onDelete('set null');
            $table->json('meta')->nullable(); // Store payment gateway response
            $table->timestamps();
            
            $table->index(['vendor_id', 'created_at']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

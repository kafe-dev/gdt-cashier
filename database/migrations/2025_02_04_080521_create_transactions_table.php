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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('transaction_event_code');
            $table->timestamp('transaction_initiation_date')->nullable();
            $table->timestamp('transaction_updated_date')->nullable();
            $table->string('transaction_amount_currency');
            $table->decimal('transaction_amount_value', 15, 2);
            $table->string('transaction_status');
            $table->text('transaction_subject')->nullable();
            $table->string('ending_balance_currency')->nullable();
            $table->decimal('ending_balance_value', 15, 2)->nullable();
            $table->string('available_balance_currency')->nullable();
            $table->decimal('available_balance_value', 15, 2)->nullable();
            $table->string('protection_eligibility')->nullable();
            $table->json('payer_info')->nullable();
            $table->json('shipping_info')->nullable();
            $table->json('cart_info')->nullable();
            $table->json('store_info')->nullable();
            $table->json('incentive_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

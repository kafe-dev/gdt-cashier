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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paygate_id')->unsigned();
            $table->string('dispute_id');
            $table->string('buyer_transaction_id')->nullable();
            $table->string('merchant_id')->nullable()->default('');
            $table->string('reason')->nullable();
            $table->string('status')->nullable();
            $table->string('dispute_state')->nullable();
            $table->string('dispute_amount_currency', 10)->nullable();
            $table->decimal('dispute_amount_value', 15)->nullable();
            $table->string('dispute_life_cycle_stage')->nullable();
            $table->string('dispute_channel')->nullable();
            $table->timestamp('seller_response_due_date')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};

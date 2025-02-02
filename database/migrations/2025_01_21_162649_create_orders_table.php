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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paygate_id')->unsigned();
            $table->string('code')->unique();
            $table->enum('status', ['PAID', 'NEW'])->default('NEW');
            $table->string('invoicer_email_address');
            $table->json('billing_info')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency_code', 10);
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->string('paid_currency_code', 10)->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

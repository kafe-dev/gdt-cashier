<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paypal_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('timezone')->nullable();
            $table->string('paygate_id')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('event_code')->nullable();
            $table->string('status')->nullable();
            $table->string('currency')->nullable();
            $table->string('gross')->nullable();
            $table->string('fee')->nullable();
            $table->string('net')->nullable();
            $table->string('from_email_address')->nullable();
            $table->string('to_email_address')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('address_status')->nullable();
            $table->string('item_title')->nullable();
            $table->string('item_id')->nullable();
            $table->string('shipping_and_handling_amount')->nullable();
            $table->string('insurance_amount')->nullable();
            $table->string('sales_tax')->nullable();
            $table->string('option_1_name')->nullable();
            $table->string('option_1_value')->nullable();
            $table->string('option_2_name')->nullable();
            $table->string('option_2_value')->nullable();
            $table->string('reference_txn_id')->nullable();
//            $table->string('invoice_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('custom_number')->nullable();
            $table->string('quantity')->nullable();
            $table->string('receipt_id')->nullable();
            $table->string('balance')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('town_city')->nullable();
            $table->string('state_province')->nullable();
            $table->string('zip_postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('contact_phone_number')->nullable();
            $table->string('subject')->nullable();
            $table->text('note')->nullable();
            $table->string('country_code')->nullable();
            $table->string('balance_impact')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('exported_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_paypal_transactions');
    }
};

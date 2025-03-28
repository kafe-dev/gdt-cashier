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
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paygate_id')->unsigned();
            $table->string('paygate_name')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('courier_code')->nullable();
            $table->string('tracking_status')->nullable();
            $table->json('tracking_data')->nullable();
            $table->smallInteger('type')->default(0);
            $table->boolean('has_tracking_number')->default(0);
            $table->timestamp('ordered_at')->nullable();
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
        Schema::dropIfExists('order_tracking');
    }

};

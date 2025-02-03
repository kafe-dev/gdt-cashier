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
        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('paygate_id')->references('id')->on('paygates')->onDelete('RESTRICT')->onUpdate('CASCADE');
        });
        Schema::table('stores', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
        Schema::table('order_tracking', function(Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
        Schema::table('disputes', function(Blueprint $table) {
            $table->foreign('paygate_id')->references('id')->on('paygates')->onDelete('RESTRICT')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

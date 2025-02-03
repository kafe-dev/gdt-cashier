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
        Schema::create('paygates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->json('api_data')->nullable();
            $table->json('vps_data')->nullable();
            $table->smallInteger('type')->default(0);
            $table->smallInteger('status')->default(0);
            $table->timestamps();
            $table->decimal('limitation', 65)->default(0.00);
            $table->smallInteger('mode')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paygates');
    }
};

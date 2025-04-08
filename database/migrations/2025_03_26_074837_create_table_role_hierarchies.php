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
        Schema::create('role_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_role');
            $table->integer('child_role')->nullable();
            $table->timestamps();

            $table->unique(['parent_role', 'child_role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_role_hierarchies');
    }
};

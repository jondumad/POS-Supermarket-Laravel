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
        // First, make user_id nullable if it's not already
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Then drop the existing foreign key
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Re-add the foreign key with onDelete('set null')
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // Re-add the original foreign key
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });

        // Make user_id not nullable again
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};

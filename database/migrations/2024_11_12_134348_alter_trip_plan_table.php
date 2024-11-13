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
        Schema::table('trip_plans', function (Blueprint $table) {
            $table->dateTime('actual_start')->nullable()->change();
            $table->dateTime('actual_finish')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_plans', function (Blueprint $table) {
            $table->dateTime('actual_start')->nullable(false)->change();
            $table->dateTime('actual_finish')->nullable(false)->change();
        });
    }
};

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
        Schema::rename('work_trip_details', 'work_trip_in_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('work_trip_in_details', 'work_trip_details');
    }
};

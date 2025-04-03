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
        Schema::table('exact_employees', function (Blueprint $table) {
            $table->string('hourly_rate')->nullable()->after('rate');
            $table->date('start_rate_date')->nullable()->after('hourly_rate');
            $table->date('end_rate_date')->nullable()->after('start_rate_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exact_employees', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'start_rate_date', 'end_rate_date']);
        });
    }
};

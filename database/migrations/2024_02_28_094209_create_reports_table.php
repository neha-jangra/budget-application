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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->decimal('monthly_amount', 10, 2)->default(0);
            $table->decimal('months', 10, 2)->default(0);
            $table->decimal('total_annual_budget', 10, 2)->default(0);
            $table->decimal('projected_budget', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->tinyInteger('is_other_direct')->default(0);
            $table->string('other_direct_expense')->nullable();
            $table->unsignedSmallInteger('year');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

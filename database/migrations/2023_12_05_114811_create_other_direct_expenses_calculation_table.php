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
        Schema::create('other_direct_expenses_calculation', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('other_direct_expense_id')->unsigned();
            $table->string('notes')->default('per_day');
            $table->bigInteger('units')->default(0);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->decimal('total_approved_cost', 10, 2)->default(0);
            $table->decimal('actual_cost_till_date', 10, 2)->default(0);
            $table->decimal('remaining_cost', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('other_direct_expense_id')
                ->references('id')
                ->on('other_direct_expenses')
                ->onDelete('cascade')
                ->name('fk_other_direct_exp_calc_other_direct_exp_id'); // Set your custom constraint name here
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_direct_expenses_calculation');
    }
};

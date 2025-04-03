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
        Schema::create('indirect_expenses_calculations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('indirect_expense_category_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned();
            $table->string('notes')->default('per_day');
            $table->bigInteger('units')->default(0);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->decimal('total_approved_cost', 10, 2)->default(0);
            $table->decimal('actual_cost_till_date', 10, 2)->default(0);
            $table->decimal('remaining_cost', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('indirect_expense_category_id')
                ->references('id')
                ->on('indirect_cost_categories')
                ->onDelete('cascade')
                ->name('fk_indirect_exp_calc_indirect_exp_id'); // Set your custom constraint name here

            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indirect_expenses_calculations');
    }
};

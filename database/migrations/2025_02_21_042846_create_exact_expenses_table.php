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
        Schema::create('exact_expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid('expense_id')->unique();
            $table->string('budgeted_cost')->nullable();
            $table->string('budgeted_revenue')->nullable();
            $table->boolean('completed')->default(false);
            $table->string('description')->nullable();
            $table->uuid('part_of')->nullable();
            $table->string('part_of_description')->nullable();
            $table->uuid('project_id');
            $table->string('project_description')->nullable();
            $table->string('item')->nullable();
            $table->string('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exact_expenses');
    }
};

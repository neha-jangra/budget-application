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
        Schema::create('sub_projects', function (Blueprint $table) {
            $table->id();
            $table->biginteger('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->biginteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('users');
            $table->string('note',20);
            $table->string('units');
            $table->integer('unit_costs');
            $table->double('total_approval_budget')->default(0);
            $table->double('actual_expenses_to_date')->default(0);
            $table->double('remaining_balance')->default(0);
            $table->biginteger('project_hierarchy_id')->unsigned();
            $table->foreign('project_hierarchy_id')->references('id')->on('look_ups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_projects');
    }
};

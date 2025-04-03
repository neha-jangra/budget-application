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
        Schema::create('sub_project_data', function (Blueprint $table) {
            $table->id();
            $table->biginteger('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->biginteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('users');
            $table->biginteger('sub_project_id')->unsigned()->nullable();
            $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            $table->string('note',60)->nullable();
            $table->string('unit_costs')->nullable();
            $table->string('units')->nullable();
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
        Schema::dropIfExists('sub_project_data');
    }
};

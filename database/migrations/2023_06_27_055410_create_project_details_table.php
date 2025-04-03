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
        Schema::create('project_details', function (Blueprint $table) {
            $table->id();
            $table->string('expenses');
            $table->string('remaining_budget');
            $table->double('previous_budget');
            $table->double('balance_of_current_year');
            $table->biginteger('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->biginteger('sub_project_id')->unsigned();
            $table->foreign('sub_project_id')->references('id')->on('projects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('exact_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('activity_id')->unique();  // ID from API
            $table->string('budgeted_cost')->nullable();
            $table->string('budgeted_hours')->nullable();
            $table->string('budgeted_revenue')->nullable();
            $table->boolean('completed')->default(false);
            $table->string('description')->nullable();
            $table->uuid('part_of')->nullable();
            $table->string('part_of_description')->nullable();
            $table->uuid('project_id');
            $table->uuid('project_description')->nullable();;
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exact_activities');
    }
};

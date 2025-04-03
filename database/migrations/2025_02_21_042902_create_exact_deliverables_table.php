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
        Schema::create('exact_deliverables', function (Blueprint $table) {
            $table->uuid('deliverable_id')->primary();
            $table->string('description')->nullable();
            $table->uuid('part_of')->nullable();
            $table->string('part_of_description')->nullable();
            $table->uuid('project_id')->nullable();
            $table->string('project_description')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exact_deliverables');
    }
};

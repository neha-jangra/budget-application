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
        Schema::create('exact_sub_project_schemas', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('sub_project_id')->nullable();
            $table->string('description');
            $table->string('exact_id')->nullable();
            $table->string('look_up_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exact_sub_project_schemas');
    }
};

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
        Schema::create('sub_project_synced_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('look_up_id')->constrained('look_ups')->onDelete('cascade');
            $table->foreignId('sub_project_id')->constrained('sub_projects')->onDelete('cascade');
            $table->boolean('is_synced')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_project_synced_statuses');
    }
};

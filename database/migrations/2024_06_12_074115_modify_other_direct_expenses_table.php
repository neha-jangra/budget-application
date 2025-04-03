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
        Schema::table('other_direct_expenses', function (Blueprint $table) {
            $table->tinyInteger('is_project')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_direct_expenses', function (Blueprint $table) {
            $table->dropColumn('is_project');
        });
    }
};

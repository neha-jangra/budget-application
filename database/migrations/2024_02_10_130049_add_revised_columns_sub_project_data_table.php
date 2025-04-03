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
        Schema::table('sub_project_data', function (Blueprint $table) {
            $table->double('revised_units')->default(0);
            $table->double('revised_unit_amount')->default(0);
            $table->double('revised_new_budget')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_project_data', function (Blueprint $table) {
            $table->dropColumn('revised_units');
            $table->dropColumn('revised_unit_amount');
            $table->dropColumn('revised_new_budget');
        });
    }
};

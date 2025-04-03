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
        Schema::table('sub_projects', function (Blueprint $table) {

            $table->bigInteger('project_hierarchy_id')->nullable()->unsigned()->change();
            $table->bigInteger('employee_id')->nullable()->unsigned()->change();
            $table->string('note')->nullable()->change();
            $table->string('unit_costs', 255)->nullable()->change();
            $table->string('units', 50)->nullable()->change()->after('unit_costs');
        });

        Schema::table('project_details', function (Blueprint $table) {

            $table->string('expenses')->nullable()->change();
            $table->string('remaining_budget')->nullable()->change();
            $table->double('previous_budget')->nullable()->change();
            $table->double('balance_of_current_year')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_projects', function (Blueprint $table) {
            //
        });
    }
};

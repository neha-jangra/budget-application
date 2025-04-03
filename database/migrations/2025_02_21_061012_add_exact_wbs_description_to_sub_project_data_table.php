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
            $table->unsignedBigInteger('employee_id')->nullable()->change();
            $table->string('exact_wbs_description')->nullable()->after('employee_id');
            $table->string('exact_wbs_id')->nullable()->after('exact_wbs_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_project_data', function (Blueprint $table) {
            $table->string('employee_id')->nullable(false)->change();
            $table->dropColumn('exact_wbs_description');
            $table->dropColumn('exact_wbs_id');
        });
    }
};

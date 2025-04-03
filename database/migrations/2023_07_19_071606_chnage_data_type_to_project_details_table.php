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
        Schema::table('project_details', function (Blueprint $table) {
            if (!Schema::hasColumn('project_details', 'sub_project_id')) {
                $table->bigInteger('sub_project_id')->unsigned()->nullable();
                $table->foreign('sub_project_id')->references('id')->on('sub_projects');
            } else {
                $table->dropForeign(['sub_project_id']);
                $table->dropColumn('sub_project_id');
            }
            $table->double('approved_budget')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_details', function (Blueprint $table) {
            $table->dropForeign(['sub_project_id']); // Drop foreign key constraint first
            $table->dropColumn('sub_project_id');
        });
    }
};

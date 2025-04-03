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
        Schema::table('project_year_linking_with_exacts', function (Blueprint $table) {
            $table->boolean('is_syncable')->default(true)->after('exact_project_code');
            $table->boolean('has_subproject')->default(true)->after('is_syncable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_year_linking_with_exacts', function (Blueprint $table) {
            $table->dropColumn(['is_syncable', 'has_subproject']);
        });
    }
};

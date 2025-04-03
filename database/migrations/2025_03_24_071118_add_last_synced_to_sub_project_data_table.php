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
        Schema::table('sub_project_data', function (Blueprint $table) {
            $table->timestamp('last_synced_at')->nullable()->after('employee_id');
        });
    }

    public function down()
    {
        Schema::table('sub_project_data', function (Blueprint $table) {
            $table->dropColumn('last_synced_at');
        });
    }
};

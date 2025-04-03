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
        $currentYear = date('Y');

        Schema::table('indirect_expenses_calculations', function (Blueprint $table) use ($currentYear) {
            $table->unsignedSmallInteger('year')->default($currentYear);
        });

        Schema::table('other_direct_expenses_calculation', function (Blueprint $table) use ($currentYear) {
            $table->unsignedSmallInteger('year')->default($currentYear);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('indirect_expenses_calculations', function (Blueprint $table) {
            $table->dropColumn('year');
        });

        Schema::table('other_direct_expenses_calculation', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};

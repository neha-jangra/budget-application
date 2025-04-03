<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the unique index before making the column nullable
            $table->dropUnique(['email']);
            // Make the email column nullable
            $table->string('email')->nullable()->change();
            // Add the unique index back, taking into account the nullable column
            $table->unique('email');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the unique index before reverting the column to non-nullable
            $table->dropUnique(['email']);
            // Revert the email column to non-nullable
            $table->string('email')->nullable(false)->change();
            // Add the unique index back
            $table->unique('email');
        });
    }
};

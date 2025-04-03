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
        Schema::create('exact_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('refresh_token')->nullable();
            $table->text('access_token')->nullable();
            $table->string('token_type')->nullable();
            $table->text('api_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exact_tokens');
    }
};

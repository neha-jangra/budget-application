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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code',30);
            $table->string('project_name',30);
            $table->string('project_type',30);
            $table->double('budget');
            $table->biginteger('project_donor_id')->unsigned();
            $table->foreign('project_donor_id')->references('id')->on('users');
            $table->string('donor_email',30);
            $table->string('donor_phone_number',20);
            $table->string('ecnl_contact',20);
            $table->date('project_duration_from');
            $table->date('project_duration_to');
            $table->date('current_budget_timeline_from');
            $table->date('current_budget_timeline_to');
            $table->date('date_prepared');
            $table->date('date_revised');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

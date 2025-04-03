<?php

use App\Models\InDirectExpenseCategories;
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
        Schema::dropIfExists('indirect_expenses_calculation_drafts');
        Schema::dropIfExists('other_direct_expenses_calculation');
        Schema::dropIfExists('other_direct_expenses_calculation_drafts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

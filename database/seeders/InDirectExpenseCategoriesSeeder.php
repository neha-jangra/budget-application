<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{InDirectExpenseCategories};

class InDirectExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Administration',
            'Management',
            'Program Development',
            'Public Holidays+Vacations',
            'Board'
        ];

        foreach ($categories as $category) {
            // Check if the category already exists
            if (!InDirectExpenseCategories::where('name', $category)->exists()) {
                // Use the model to create a new record
                InDirectExpenseCategories::create(['name' => $category]);
            }
        }
    }
}

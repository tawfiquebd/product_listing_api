<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->truncate();

        Category::query()->insert([
            [
                'name' => 'Electronics',
                'created_at' => now(),
            ],
            [
                'name' => 'Clothing',
                'created_at' => now(),
            ],
            [
                'name' => 'Home & Kitchen',
                'created_at' => now(),
            ],
            [
                'name' => 'Books',
                'created_at' => now(),
            ],
        ]);
    }
}

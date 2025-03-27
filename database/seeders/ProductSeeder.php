<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Truncating products table with data!!");

        Product::query()->truncate();
        $faker = Faker::create();

        $products = [];
        foreach (range(1, 20) as $i) {
            $randomColor = sprintf('%06X', mt_rand(0, 0xFFFFFF));

            $products[] = [
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 1000),
                'category_id' => rand(1, 4),
                'image_url' => "https://placehold.co/600x400/$randomColor/FFF?text=Product+$i",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Product::query()->insert($products);

        $this->command->info('Seed Done!!');
    }
}

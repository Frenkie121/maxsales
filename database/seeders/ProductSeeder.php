<?php

namespace Database\Seeders;

use App\Models\{Brand, Category, Product};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::factory(5)->create();
        Category::factory(5)->create();
        Product::factory(10)->create();
    }
}

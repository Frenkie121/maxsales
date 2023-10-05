<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'Bar 1',
        ]);

        Store::create([
            'name' => 'Bar 2',
        ]);
        
        Store::create([
            'name' => 'Cave',
        ]);
        
        Store::create([
            'name' => 'Restaurant',
        ]);
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StoreSeeder::class,
            RoleSeeder::class,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin Max Sales',
            'login' => 'ADMIN699',
            'role_id' => 1
        ]);
        \App\Models\User::factory(9)->create();
    }
}

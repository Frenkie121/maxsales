<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--- Permissions -----|
        */
        // Stores Permissions
        $createStore = Permission::create(['name' => 'Create store']);
        $editStore = Permission::create(['name' => 'Edit store']);
        $viewStores = Permission::create(['name' => 'View stores']);

        // Users Permissions
        $createUser = Permission::create(['name' => 'Create user']);
        $editUser = Permission::create(['name' => 'Edit user']);
        $viewUsers = Permission::create(['name' => 'View users']);
        $disableUser = Permission::create(['name' => 'Disable users']);

        // Product Permissions
        $createProduct = Permission::create(['name' => 'Create product']);
        $editProduct = Permission::create(['name' => 'Edit product']);
        $viewProducts = Permission::create(['name' => 'View products']);
        $disableProduct = Permission::create(['name' => 'Disable products']); 

        /*
        |--- Roles and assignations -----|
        */
        $admin = Role::create(['name' => 'Administrator'])
                    ->givePermissionTo(Permission::all());
        $manager = Role::create(['name' => 'Manager'])
                    ->givePermissionTo([
                        $createProduct,
                        $editProduct,
                        $viewProducts,
                        $disableProduct,
                        $createUser,
                        $editUser,
                        $disableUser,
                        $viewUsers,
                    ]);
        $accountant = Role::create(['name' => 'Accountant'])
                        ->givePermissionTo([$createProduct, $editProduct, $viewProducts, $disableProduct]);

        $cashier = Role::create(['name' => 'Cashier']);
        $server = Role::create(['name' => 'Server']);

        /*
        |--- Assign Roles to Users -----|
        */
        User::factory()->create([
            'name' => 'Admin Max Sales',
            'login' => 'ADMIN699',
        ])->assignRole($admin);

        User::factory()->create([
            'name' => 'Manager Max Sales',
            'login' => 'MANAGER688',
        ])->assignRole($manager);

        User::factory()->create([
            'name' => 'Accountant Max Sales',
            'login' => 'ACCOUNTANT233',
        ])->assignRole($accountant);
        
        $users = \App\Models\User::factory(9)
            ->create();
        $users->each(fn ($user) => $user->assignRole(fake()->randomElement([$cashier, $server])));
    }
}
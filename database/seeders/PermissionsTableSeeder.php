<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Picture permissions
        Permission::create(['name' => 'view pictures']);
        Permission::create(['name' => 'upload pictures']);
        Permission::create(['name' => 'edit pictures']);
        Permission::create(['name' => 'delete pictures']);

        // Order permissions
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'process orders']);
        Permission::create(['name' => 'manage orders']);

        // Event permissions
        Permission::create(['name' => 'view events']);
        Permission::create(['name' => 'create events']);
        Permission::create(['name' => 'edit events']);
        Permission::create(['name' => 'delete events']);
    }
}

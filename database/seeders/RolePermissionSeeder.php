<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Admin Role
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

        // Manager Role
        $manager = Role::findByName('manager');
        $manager->givePermissionTo([
            'view users', 'create users', 'edit users', 'delete users',
            'view pictures', 'edit pictures', 'delete pictures',
            'view orders', 'create orders', 'edit orders',
            'view events', 'create events', 'edit events', 'delete events'
        ]);

        // Photographer Role
        $photographer = Role::findByName('photographer');
        $photographer->givePermissionTo([
            'view pictures', 'create pictures', 'edit pictures', 'delete pictures',
            'view events', 'create events', 'edit events'
        ]);

        // Photographer Role
        $user = Role::findByName('user');
        $user->givePermissionTo([
            'view pictures', 'create pictures', 'edit pictures', 'delete pictures',
            'view events', 'create events', 'edit events'
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin Peter',
            'email' => 'boyiajas@gmail.com',
            'phone_number' => '0842575612',
            'status' => 'active',
            'position' => 'Software Developer',
            'password' => bcrypt('gospel123')
        ]);
        $user->assignRole($admin);
    }
}



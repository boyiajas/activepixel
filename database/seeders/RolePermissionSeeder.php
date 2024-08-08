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
            'view orders', 'process orders', 'manage orders',
            'view events', 'create events', 'edit events', 'delete events'
        ]);

        // Photographer Role
        $photographer = Role::findByName('photographer');
        $photographer->givePermissionTo([
            'view pictures', 'upload pictures', 'edit pictures', 'delete pictures',
            'view events', 'create events', 'edit events'
        ]);
    }
}

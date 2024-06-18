<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = Role::create(['name' => 'user',]);
        $admin = Role::create(['name' => 'admin']);

        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        Role::create(['name' => 'user', 'guard_name' => 'api']);

        $permission1 = Permission::create(['name' => 'view book']);
        $permission2 = Permission::create(['name' => 'edit book']);
        $permission3 = Permission::create(['name' => 'add book']);
        $permission4 = Permission::create(['name' => 'delete book']);

        $permission5 = Permission::create(['name' => 'view user']);
        $permission6 = Permission::create(['name' => 'edit user']);
        $permission7 = Permission::create(['name' => 'add user']);
        $permission8 = Permission::create(['name' => 'delete user']);

        $permission9 = Permission::create(['name' => 'view cart']);
        $permission10 = Permission::create(['name' => 'edit cart']);
        $permission11 = Permission::create(['name' => 'add cart']);
        $permission12 = Permission::create(['name' => 'delete cart']);

        $permission13 = Permission::create(['name' => 'view order']);
        $permission = Permission::create(['name' => 'edit order']);
        $permission14 = Permission::create(['name' => 'add order']);
        $permission15 = Permission::create(['name' => 'delete order']);

        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo([$permission9, $permission10, $permission11, $permission12, $permission13, $permission14, $permission15]);
    }
}

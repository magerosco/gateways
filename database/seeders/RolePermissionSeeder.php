<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'gateway.index']);
        Permission::create(['name' => 'gateway.show']);
        Permission::create(['name' => 'gateway.store']);
        Permission::create(['name' => 'gateway.update']);
        Permission::create(['name' => 'gateway.destroy']);

        Permission::create(['name' => 'peripheral.index']);
        Permission::create(['name' => 'peripheral.show']);
        Permission::create(['name' => 'peripheral.store']);
        Permission::create(['name' => 'peripheral.update']);
        Permission::create(['name' => 'peripheral.destroy']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $user = \App\Models\User::where('name', 'admin')->first();
        $user->assignRole('admin');
    }
}

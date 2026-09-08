<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Tenants
            'tenants.view',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            // Plans
            'plans.view',
            'plans.create',
            'plans.edit',
            'plans.delete',
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            // Roles & Permissions
            'roles.view',
            'roles.manage',
            // Logs
            'logs.view',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'admin',
            ]);
        }

        // Create super_admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin',
        ]);
        $superAdminRole->syncPermissions($permissions);

        // Create support role
        $supportRole = Role::firstOrCreate([
            'name' => 'support',
            'guard_name' => 'admin',
        ]);
        $supportRole->syncPermissions(['tenants.view', 'logs.view']);

        // Create billing_manager role
        $billingRole = Role::firstOrCreate([
            'name' => 'billing_manager',
            'guard_name' => 'admin',
        ]);
        $billingRole->syncPermissions(['plans.view', 'plans.create', 'plans.edit', 'tenants.view']);

        // Attach super_admin role to master admin
        $admin = Admin::where('email', 'admin@compitator.com')->first();
        if ($admin) {
            $admin->syncRoles(['super_admin']);
        }
    }
}

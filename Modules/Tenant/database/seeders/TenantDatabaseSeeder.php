<?php

namespace Modules\Tenant\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = function_exists('tenant') ? tenant() : null;
        $tenantId = $tenant ? $tenant->getTenantKey() : 'default';

        $name = $tenant->company_name ?? ($tenant->name ?? 'Tenant Admin');
        $email = $tenant->email ?? "admin@{$tenantId}.localhost";

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}

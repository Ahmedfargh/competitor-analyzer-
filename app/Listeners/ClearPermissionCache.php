<?php

namespace App\Listeners;

use Spatie\Permission\PermissionRegistrar;

class ClearPermissionCache
{
    /**
     * Clear Spatie in-memory and store permission cache on tenancy context switch.
     */
    public function handle(mixed $event = null): void
    {
        try {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        } catch (\Throwable) {
            // Ignore if cache table does not exist yet during initial tenant migration
        }
    }
}

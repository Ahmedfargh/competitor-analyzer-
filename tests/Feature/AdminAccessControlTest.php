<?php

namespace Tests\Feature;

use App\Livewire\Admin\Permissions\PermissionManager;
use App\Livewire\Admin\Roles\RoleManager;
use App\Livewire\Admin\Users\AdminUserManager;
use Database\Seeders\AdminRbacSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Modules\Admin\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessControlTest extends TestCase
{
    use DatabaseTransactions;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            LaravelLocalizationRedirectFilter::class,
            LocaleSessionRedirect::class,
        ]);

        $this->seed(AdminRbacSeeder::class);

        $this->admin = Admin::firstOrCreate(
            ['email' => 'master-rbac-admin@compitator.com'],
            [
                'name' => 'RBAC Admin',
                'password' => bcrypt('password'),
            ]
        );
    }

    public function test_admin_can_access_users_roles_permissions_index(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.users.index'))
            ->assertStatus(200);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.roles.index'))
            ->assertStatus(200);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.permissions.index'))
            ->assertStatus(200);
    }

    public function test_admin_user_manager_livewire_creates_and_updates_admin(): void
    {
        $email = 'new-admin-'.uniqid().'@compitator.com';

        Livewire::actingAs($this->admin, 'admin')
            ->test(AdminUserManager::class)
            ->call('openCreateModal')
            ->set('name', 'Junior Ops')
            ->set('email', $email)
            ->set('password', 'Secur3Passw0rd!')
            ->set('password_confirmation', 'Secur3Passw0rd!')
            ->set('selectedRoles', ['support'])
            ->call('createUser')
            ->assertHasNoErrors();

        $created = Admin::where('email', $email)->first();
        $this->assertNotNull($created);
        $this->assertTrue($created->hasRole('support'));

        // Update
        Livewire::actingAs($this->admin, 'admin')
            ->test(AdminUserManager::class)
            ->call('openEditModal', $created->id)
            ->set('name', 'Senior Ops')
            ->set('selectedRoles', ['support', 'billing_manager'])
            ->call('updateUser')
            ->assertHasNoErrors();

        $created->refresh();
        $this->assertEquals('Senior Ops', $created->name);
        $this->assertTrue($created->hasRole('billing_manager'));
    }

    public function test_admin_user_manager_livewire_prevents_self_deletion(): void
    {
        Livewire::actingAs($this->admin, 'admin')
            ->test(AdminUserManager::class)
            ->call('deleteUser', $this->admin->id)
            ->assertHasErrors(['user']);

        $this->assertDatabaseHas('admins', ['id' => $this->admin->id]);
    }

    public function test_role_manager_livewire_creates_role_with_permissions(): void
    {
        $roleName = 'security_officer_'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(RoleManager::class)
            ->call('openCreateModal')
            ->set('name', $roleName)
            ->set('selectedPermissions', ['tenants.view', 'logs.view'])
            ->call('saveRole')
            ->assertHasNoErrors();

        $role = Role::where('name', $roleName)->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('tenants.view'));
        $this->assertTrue($role->hasPermissionTo('logs.view'));
    }

    public function test_role_manager_livewire_prevents_deleting_super_admin(): void
    {
        $superAdmin = Role::where('name', 'super_admin')->where('guard_name', 'admin')->first();

        Livewire::actingAs($this->admin, 'admin')
            ->test(RoleManager::class)
            ->call('deleteRole', $superAdmin->id)
            ->assertHasErrors(['role']);

        $this->assertDatabaseHas('roles', ['id' => $superAdmin->id]);
    }

    public function test_permission_manager_livewire_creates_and_deletes_permission(): void
    {
        $permName = 'analytics.export_'.uniqid();

        Livewire::actingAs($this->admin, 'admin')
            ->test(PermissionManager::class)
            ->call('openCreateModal')
            ->set('name', $permName)
            ->call('createPermission')
            ->assertHasNoErrors();

        $perm = Permission::where('name', $permName)->first();
        $this->assertNotNull($perm);

        Livewire::actingAs($this->admin, 'admin')
            ->test(PermissionManager::class)
            ->call('deletePermission', $perm->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('permissions', ['id' => $perm->id]);
    }

    public function test_activity_logs_recorded_on_rbac_changes(): void
    {
        $email = 'audit-admin-'.uniqid().'@compitator.com';

        Livewire::actingAs($this->admin, 'admin')
            ->test(AdminUserManager::class)
            ->call('openCreateModal')
            ->set('name', 'Audit Subject')
            ->set('email', $email)
            ->set('password', 'Secur3Passw0rd!')
            ->set('password_confirmation', 'Secur3Passw0rd!')
            ->call('createUser');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'admin_user.created',
        ]);
    }
}

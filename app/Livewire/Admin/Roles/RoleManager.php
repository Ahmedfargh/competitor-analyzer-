<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Admin\DTOs\Access\RoleDTO;
use Modules\Admin\Services\Contracts\PermissionServiceInterface;
use Modules\Admin\Services\Contracts\RoleServiceInterface;

class RoleManager extends Component
{
    public bool $showModal = false;

    public ?int $editingRoleId = null;

    public string $name = '';

    /**
     * @var array<int, string>
     */
    public array $selectedPermissions = [];

    public ?string $statusMessage = null;

    public function openCreateModal(): void
    {
        $this->reset(['editingRoleId', 'name', 'selectedPermissions']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(int $id, RoleServiceInterface $roleService): void
    {
        $role = $roleService->getRole($id);
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function saveRole(RoleServiceInterface $roleService): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'name')
                    ->where('guard_name', 'admin')
                    ->ignore($this->editingRoleId),
            ],
            'selectedPermissions' => ['nullable', 'array'],
        ]);

        $dto = new RoleDTO(
            name: $this->name,
            guardName: 'admin',
            permissions: $this->selectedPermissions
        );

        if ($this->editingRoleId) {
            $roleService->updateRole($this->editingRoleId, $dto);
            $this->statusMessage = __('admin.role_updated_successfully');
        } else {
            $roleService->createRole($dto);
            $this->statusMessage = __('admin.role_created_successfully');
        }

        $this->showModal = false;
        $this->reset(['editingRoleId', 'name', 'selectedPermissions']);
    }

    public function deleteRole(int $id, RoleServiceInterface $roleService): void
    {
        try {
            $roleService->deleteRole($id);
            $this->statusMessage = __('admin.role_deleted_successfully');
        } catch (\Throwable $e) {
            $this->addError('role', $e->getMessage());
        }
    }

    public function render(RoleServiceInterface $roleService, PermissionServiceInterface $permissionService)
    {
        $roles = $roleService->getAllRoles();
        $groupedPermissions = $permissionService->getGroupedPermissions();

        return view('livewire.admin.roles.role-manager', [
            'roles' => $roles,
            'groupedPermissions' => $groupedPermissions,
        ]);
    }
}

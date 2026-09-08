<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Modules\Admin\DTOs\Access\PermissionDTO;
use Modules\Admin\Services\Contracts\PermissionServiceInterface;

class PermissionManager extends Component
{
    public bool $showModal = false;

    public string $name = '';

    public string $group = '';

    public string $filterGroup = '';

    public ?string $statusMessage = null;

    public function openCreateModal(): void
    {
        $this->reset(['name', 'group']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function createPermission(PermissionServiceInterface $permissionService): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:50', 'unique:permissions,name,NULL,id,guard_name,admin'],
            'group' => ['nullable', 'string', 'max:50'],
        ]);

        $dto = new PermissionDTO(
            name: $this->name,
            guardName: 'admin',
            group: ! empty($this->group) ? $this->group : null
        );

        $permissionService->createPermission($dto);

        $this->statusMessage = __('admin.permission_created_successfully');
        $this->showModal = false;
        $this->reset(['name', 'group']);
    }

    public function deletePermission(int $id, PermissionServiceInterface $permissionService): void
    {
        $permissionService->deletePermission($id);
        $this->statusMessage = __('admin.permission_deleted_successfully');
    }

    public function render(PermissionServiceInterface $permissionService)
    {
        $grouped = $permissionService->getGroupedPermissions();

        if (! empty($this->filterGroup)) {
            $grouped = array_filter(
                $grouped,
                fn ($key) => $key === $this->filterGroup,
                ARRAY_FILTER_USE_KEY
            );
        }

        $allGroups = array_keys($permissionService->getGroupedPermissions());

        return view('livewire.admin.permissions.permission-manager', [
            'groupedPermissions' => $grouped,
            'allGroups' => $allGroups,
        ]);
    }
}

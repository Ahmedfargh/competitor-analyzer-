<?php

namespace App\Livewire\Admin\Users;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Admin\DTOs\Access\AdminUserDTO;
use Modules\Admin\Services\Contracts\AdminUserServiceInterface;
use Modules\Admin\Services\Contracts\RoleServiceInterface;

class AdminUserManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingUserId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * @var array<int, string>
     */
    public array $selectedRoles = [];

    public ?string $statusMessage = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingUserId', 'name', 'email', 'password', 'password_confirmation', 'selectedRoles']);
        $this->resetValidation();
        $this->showCreateModal = true;
    }

    public function openEditModal(int $id, AdminUserServiceInterface $userService): void
    {
        $user = $userService->getUser($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = $user->roles->pluck('name')->toArray();

        $this->resetValidation();
        $this->showEditModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->resetValidation();
    }

    public function createUser(AdminUserServiceInterface $userService): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', Password::defaults(), 'same:password_confirmation'],
            'selectedRoles' => ['nullable', 'array'],
        ]);

        $dto = new AdminUserDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            roles: $this->selectedRoles
        );

        $userService->createUser($dto);

        $this->statusMessage = __('admin.user_created_successfully');
        $this->showCreateModal = false;
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'selectedRoles']);
    }

    public function updateUser(AdminUserServiceInterface $userService): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($this->editingUserId),
            ],
            'password' => ['nullable', 'string', Password::defaults(), 'same:password_confirmation'],
            'selectedRoles' => ['nullable', 'array'],
        ]);

        $dto = new AdminUserDTO(
            name: $this->name,
            email: $this->email,
            password: ! empty($this->password) ? $this->password : null,
            roles: $this->selectedRoles
        );

        $userService->updateUser($this->editingUserId, $dto);

        $this->statusMessage = __('admin.user_updated_successfully');
        $this->showEditModal = false;
        $this->reset(['editingUserId', 'name', 'email', 'password', 'password_confirmation', 'selectedRoles']);
    }

    public function deleteUser(int $id, AdminUserServiceInterface $userService): void
    {
        try {
            $userService->deleteUser($id);
            $this->statusMessage = __('admin.user_deleted_successfully');
        } catch (\Throwable $e) {
            $this->addError('user', $e->getMessage());
        }
    }

    public function render(AdminUserServiceInterface $userService, RoleServiceInterface $roleService)
    {
        $filters = [
            'search' => $this->search,
            'role' => $this->roleFilter,
        ];

        $users = $userService->listUsers($filters, 10);
        $roles = $roleService->getAllRoles();

        return view('livewire.admin.users.admin-user-manager', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}

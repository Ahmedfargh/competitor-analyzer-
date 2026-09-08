<?php

namespace App\Livewire\Admin\Tenants;

use Livewire\Component;
use Modules\Admin\Services\Contracts\TenantServiceInterface;

class TenantDataBrowser extends Component
{
    public string $tenantId;

    public string $userSearch = '';

    public bool $showCreateUserModal = false;

    public string $newUserName = '';

    public string $newUserEmail = '';

    public string $newUserPassword = '';

    public ?string $feedbackMessage = null;

    public string $feedbackType = 'success';

    /**
     * Validation rules for adding a tenant user.
     *
     * @var array<string, string>
     */
    protected array $rules = [
        'newUserName' => 'required|string|max:255',
        'newUserEmail' => 'required|email|max:255',
        'newUserPassword' => 'required|string|min:8',
    ];

    public function mount(string $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function openCreateUserModal(): void
    {
        $this->reset(['newUserName', 'newUserEmail', 'newUserPassword', 'feedbackMessage']);
        $this->showCreateUserModal = true;
    }

    public function closeCreateUserModal(): void
    {
        $this->showCreateUserModal = false;
        $this->reset(['newUserName', 'newUserEmail', 'newUserPassword']);
    }

    public function saveTenantUser(TenantServiceInterface $tenantService): void
    {
        $this->validate();

        try {
            $tenantService->createTenantUser($this->tenantId, [
                'name' => $this->newUserName,
                'email' => $this->newUserEmail,
                'password' => $this->newUserPassword,
            ]);

            $this->feedbackType = 'success';
            $this->feedbackMessage = "Tenant user '{$this->newUserEmail}' created successfully.";
            $this->closeCreateUserModal();
        } catch (\Throwable $e) {
            $this->feedbackType = 'error';
            $this->feedbackMessage = 'Failed to create tenant user: '.$e->getMessage();
        }
    }

    public function deleteTenantUser(TenantServiceInterface $tenantService, int|string $userId): void
    {
        try {
            $deleted = $tenantService->deleteTenantUser($this->tenantId, $userId);
            if ($deleted) {
                $this->feedbackType = 'success';
                $this->feedbackMessage = 'Tenant user removed successfully.';
            } else {
                $this->feedbackType = 'error';
                $this->feedbackMessage = 'Could not find or remove tenant user.';
            }
        } catch (\Throwable $e) {
            $this->feedbackType = 'error';
            $this->feedbackMessage = 'Failed to delete user: '.$e->getMessage();
        }
    }

    public function render(TenantServiceInterface $tenantService)
    {
        $data = $tenantService->browseTenantData($this->tenantId);

        // If userSearch is provided and recentUsers exists, filter in memory
        if (! empty($this->userSearch) && ! empty($data['recent_users'])) {
            $search = strtolower($this->userSearch);
            $data['recent_users'] = array_filter($data['recent_users'], function ($u) use ($search) {
                return str_contains(strtolower($u->name ?? ''), $search)
                    || str_contains(strtolower($u->email ?? ''), $search);
            });
        }

        return view('livewire.admin.tenants.tenant-data-browser', [
            'tenant' => $data['tenant'],
            'domains' => $data['domains'],
            'databaseStatus' => $data['database_status'],
            'tables' => $data['tables'],
            'recentUsers' => $data['recent_users'],
            'counts' => $data['counts'],
            'error' => $data['error'],
        ]);
    }
}

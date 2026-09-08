<?php

namespace Modules\Admin\Http\Requests\Tenant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Contracts\CreateTenantDTOInterface;
use Modules\Admin\DTOs\Tenant\CreateTenantDTO;
use Modules\Admin\Http\Requests\Contracts\StoreTenantRequestInterface;

class StoreTenantRequest extends FormRequest implements StoreTenantRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:tenants,id'],
            'company_name' => ['required', 'string', 'min:2', 'max:100'],
            'domain' => ['required', 'string', 'min:3', 'max:100', 'unique:domains,domain'],
            'plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
        ];
    }

    public function getTenantId(): string
    {
        return (string) $this->validated('id');
    }

    public function getCompanyName(): string
    {
        return (string) $this->validated('company_name');
    }

    public function getDomain(): string
    {
        $rawDomain = trim((string) $this->validated('domain'));
        $rawDomain = preg_replace('#^https?://#i', '', $rawDomain);
        $rawDomain = explode('/', $rawDomain)[0];
        $rawDomain = explode(':', $rawDomain)[0];
        $rawDomain = strtolower(trim($rawDomain));

        if (! str_contains($rawDomain, '.')) {
            $centralHost = request()->getHost() ?: 'localhost';
            $rawDomain = "{$rawDomain}.{$centralHost}";
        }

        return $rawDomain;
    }

    public function getPlanId(): ?int
    {
        return $this->validated('plan_id') ? (int) $this->validated('plan_id') : null;
    }

    public function toDTO(): CreateTenantDTOInterface
    {
        return new CreateTenantDTO(
            tenantId: $this->getTenantId(),
            companyName: $this->getCompanyName(),
            domain: $this->getDomain(),
            planId: $this->getPlanId(),
            metadata: [
                'created_by' => auth('admin')->id(),
                'ip' => $this->ip(),
            ]
        );
    }
}

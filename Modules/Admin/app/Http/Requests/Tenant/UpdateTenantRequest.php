<?php

namespace Modules\Admin\Http\Requests\Tenant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Admin\DTOs\Contracts\UpdateTenantDTOInterface;
use Modules\Admin\DTOs\Tenant\UpdateTenantDTO;
use Modules\Admin\Http\Requests\Contracts\UpdateTenantRequestInterface;

class UpdateTenantRequest extends FormRequest implements UpdateTenantRequestInterface
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
        $tenantId = $this->route('tenant') ?? $this->route('id');

        return [
            'company_name' => ['sometimes', 'required', 'string', 'min:2', 'max:100'],
            'domain' => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'plan_id' => ['nullable', 'integer', 'exists:subscription_plans,id'],
            'product_name' => ['nullable', 'string', 'max:150'],
            'value_proposition' => ['nullable', 'string', 'max:1000'],
            'pricing_summary' => ['nullable', 'string', 'max:500'],
            'key_differentiators' => ['nullable'],
            'target_icp' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function getCompanyName(): ?string
    {
        return $this->validated('company_name');
    }

    public function getDomain(): ?string
    {
        if (! $this->has('domain')) {
            return null;
        }

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
        return $this->has('plan_id') && $this->validated('plan_id') !== null
            ? (int) $this->validated('plan_id')
            : null;
    }

    public function getProductProfile(): ?array
    {
        if (! $this->hasAny(['product_name', 'value_proposition', 'pricing_summary', 'key_differentiators', 'target_icp'])) {
            return null;
        }

        $diffs = $this->input('key_differentiators');
        if (is_string($diffs)) {
            $diffs = array_values(array_filter(array_map('trim', explode("\n", $diffs))));
        }

        return [
            'product_name' => $this->input('product_name') ?: $this->getCompanyName(),
            'value_proposition' => $this->input('value_proposition'),
            'pricing_summary' => $this->input('pricing_summary'),
            'key_differentiators' => is_array($diffs) ? $diffs : [],
            'target_icp' => $this->input('target_icp'),
        ];
    }

    public function toDTO(): UpdateTenantDTOInterface
    {
        return new UpdateTenantDTO(
            companyName: $this->getCompanyName(),
            domain: $this->getDomain(),
            planId: $this->getPlanId(),
            metadata: [
                'updated_by' => auth('admin')->id(),
                'ip' => $this->ip(),
            ],
            productProfile: $this->getProductProfile()
        );
    }
}

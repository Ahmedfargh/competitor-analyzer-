<?php

namespace App\Models;

use App\Observers\TenantObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Support\TenantLayerCustomizer;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

#[ObservedBy([TenantObserver::class])]
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * The physical columns that exist on the tenants table.
     * Non-custom columns are automatically packed into the virtual data JSON column.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'created_at',
            'updated_at',
            'data',
        ];
    }

    /**
     * Get the tenant's company name with graceful fallback to id.
     */
    public function getCompanyNameAttribute(): string
    {
        return $this->attributes['company_name']
            ?? (is_array($this->data) && isset($this->data['company_name']) ? $this->data['company_name'] : null)
            ?? $this->id;
    }

    /**
     * Get the tenant's primary domain name.
     */
    public function getPrimaryDomainAttribute(): ?string
    {
        return $this->domains->first()?->domain;
    }

    /**
     * Get the tenant's full web URL including active port and scheme.
     */
    public function getPrimaryUrlAttribute(): ?string
    {
        $domain = $this->primary_domain;
        if (! $domain) {
            return null;
        }

        $scheme = request()->getScheme() ?: 'http';
        $port = request()->getPort();
        $portSuffix = ($port && ! in_array((int) $port, [80, 443], true)) ? ":{$port}" : '';

        if (str_contains($domain, ':')) {
            return "{$scheme}://{$domain}";
        }

        return "{$scheme}://{$domain}{$portSuffix}";
    }

    /**
     * Get the tenant's assigned plan if any.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    /**
     * Get the tenant's feature customizations.
     */
    public function featureCustomizations(): HasMany
    {
        return $this->hasMany(TenantFeatureCustomization::class, 'tenant_id');
    }

    /**
     * Resolve a feature class for this tenant looking up base class and its alternative.
     */
    public function resolveFeatureClass(string $baseClass, ?string $defaultImplementation = null): mixed
    {
        return TenantLayerCustomizer::resolve($baseClass, $defaultImplementation, $this->id);
    }

    /**
     * Get the tenant's structured product profile for competitive context matching.
     *
     * @return array<string, mixed>
     */
    public function getProductProfileAttribute(): array
    {
        $profile = $this->attributes['product_profile']
            ?? (is_array($this->data) && isset($this->data['product_profile']) ? $this->data['product_profile'] : null)
            ?? [];

        if (is_string($profile)) {
            $profile = json_decode($profile, true) ?? [];
        }

        return [
            'product_name' => $profile['product_name'] ?? $this->company_name,
            'value_proposition' => $profile['value_proposition'] ?? null,
            'pricing_summary' => $profile['pricing_summary'] ?? null,
            'key_differentiators' => $profile['key_differentiators'] ?? [],
            'target_icp' => $profile['target_icp'] ?? null,
            'tracked_competitors' => $profile['tracked_competitors'] ?? [],
        ];
    }

    /**
     * Update the tenant's product profile.
     *
     * @param  array<string, mixed>  $profile
     */
    public function updateProductProfile(array $profile): void
    {
        $current = $this->product_profile;
        $merged = array_merge($current, array_filter($profile, fn ($v) => $v !== null));

        $this->setAttribute('product_profile', $merged);
        $this->save();
    }

    /**
     * Determine if the tenant has configured a product profile.
     */
    public function hasProductProfile(): bool
    {
        $profile = $this->product_profile;

        return ! empty($profile['value_proposition']) || ! empty($profile['pricing_summary']) || ! empty($profile['key_differentiators']);
    }
}

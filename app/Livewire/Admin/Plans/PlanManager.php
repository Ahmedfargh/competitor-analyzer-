<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;
use Modules\Admin\DTOs\Plan\SubscriptionPlanDTO;
use Modules\Admin\Services\Contracts\SubscriptionPlanServiceInterface;

class PlanManager extends Component
{
    public string $currency = 'egp';

    public bool $showModal = false;

    public ?int $editingPlanId = null;

    public string $name = '';

    public string $name_en = '';

    public string $name_ar = '';

    public string $slug = '';

    public string $description = '';

    public string $description_en = '';

    public string $description_ar = '';

    public float $price_egp = 1490.00;

    public float $price_usd = 29.00;

    public string $billing_period = 'monthly';

    public string $features_raw = '';

    public string $features_en = '';

    public string $features_ar = '';

    public bool $is_popular = false;

    public bool $is_active = true;

    public int $sort_order = 1;

    public ?string $statusMessage = null;

    public string $activeTab = 'en';

    public function setCurrency(string $cur): void
    {
        $this->currency = $cur;
    }

    public function openCreateModal(): void
    {
        $this->reset([
            'editingPlanId', 'name', 'name_en', 'name_ar', 'slug',
            'description', 'description_en', 'description_ar',
            'price_egp', 'price_usd', 'billing_period',
            'features_raw', 'features_en', 'features_ar',
            'is_popular', 'is_active', 'sort_order', 'activeTab',
        ]);
        $this->price_egp = 1490.00;
        $this->price_usd = 29.00;
        $this->billing_period = 'monthly';
        $this->is_active = true;
        $this->sort_order = 1;
        $this->activeTab = 'en';
        $this->showModal = true;
    }

    public function openEditModal(int $id, SubscriptionPlanServiceInterface $planService): void
    {
        $plan = $planService->findPlan($id);
        if (! $plan) {
            return;
        }

        $this->editingPlanId = $plan->id;
        $this->name_en = (string) ($plan->getTranslation('name', 'en', false) ?: $plan->name);
        $this->name_ar = (string) ($plan->getTranslation('name', 'ar', false) ?: '');
        $this->name = $this->name_en;

        $this->slug = $plan->slug;

        $this->description_en = (string) ($plan->getTranslation('description', 'en', false) ?: ($plan->description ?? ''));
        $this->description_ar = (string) ($plan->getTranslation('description', 'ar', false) ?: '');
        $this->description = $this->description_en;

        $this->price_egp = (float) $plan->price_egp;
        $this->price_usd = (float) $plan->price_usd;
        $this->billing_period = $plan->billing_period;

        $featuresEn = $plan->getTranslation('features', 'en', false);
        if (is_array($featuresEn)) {
            $this->features_en = implode("\n", $featuresEn);
        } else {
            $this->features_en = is_array($plan->features) ? implode("\n", $plan->features) : '';
        }

        $featuresAr = $plan->getTranslation('features', 'ar', false);
        $this->features_ar = is_array($featuresAr) ? implode("\n", $featuresAr) : '';
        $this->features_raw = $this->features_en;

        $this->is_popular = (bool) $plan->is_popular;
        $this->is_active = (bool) $plan->is_active;
        $this->sort_order = (int) $plan->sort_order;
        $this->activeTab = 'en';

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function savePlan(SubscriptionPlanServiceInterface $planService): void
    {
        $this->validate([
            'slug' => ['required', 'string', 'alpha_dash', 'max:50'],
            'price_egp' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', 'string', 'in:monthly,yearly,lifetime'],
        ]);

        $enName = trim($this->name_en ?: $this->name);
        $arName = trim($this->name_ar);

        if (empty($enName) && empty($arName)) {
            $this->addError('name_en', 'Please provide a plan name.');

            return;
        }

        $name = array_filter([
            'en' => $enName,
            'ar' => $arName,
        ]);
        if (count($name) === 1 && isset($name['en']) && empty($arName)) {
            $name = ['en' => $enName];
        }

        $enDesc = trim($this->description_en ?: $this->description);
        $arDesc = trim($this->description_ar);
        $description = array_filter([
            'en' => $enDesc,
            'ar' => $arDesc,
        ]);

        $enList = array_values(array_filter(
            array_map('trim', explode("\n", $this->features_en ?: $this->features_raw)),
            fn ($item) => ! empty($item)
        ));

        $arList = array_values(array_filter(
            array_map('trim', explode("\n", $this->features_ar)),
            fn ($item) => ! empty($item)
        ));

        $features = [];
        if (! empty($enList)) {
            $features['en'] = $enList;
        }
        if (! empty($arList)) {
            $features['ar'] = $arList;
        }
        if (empty($features) && ! empty($enList)) {
            $features = $enList;
        }

        $dto = new SubscriptionPlanDTO(
            name: ! empty($name) ? $name : $enName,
            slug: $this->slug,
            description: ! empty($description) ? $description : null,
            priceEgp: $this->price_egp,
            priceUsd: $this->price_usd,
            billingPeriod: $this->billing_period,
            features: $features,
            isPopular: $this->is_popular,
            isActive: $this->is_active,
            sortOrder: $this->sort_order
        );

        $planService->savePlan($dto);

        $this->statusMessage = $this->editingPlanId
            ? __('admin.plan_updated_successfully')
            : __('admin.plan_created_successfully');

        $this->showModal = false;
    }

    public function deletePlan(int $id, SubscriptionPlanServiceInterface $planService): void
    {
        $planService->deletePlan($id);
        $this->statusMessage = __('admin.plan_deleted_successfully');
    }

    public function render(SubscriptionPlanServiceInterface $planService)
    {
        $plans = $planService->getAllPlans();

        return view('livewire.admin.plans.plan-manager', [
            'plans' => $plans,
        ]);
    }
}

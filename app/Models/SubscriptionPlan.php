<?php

namespace App\Models;

use App\Observers\SubscriptionPlanObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[ObservedBy([SubscriptionPlanObserver::class])]
class SubscriptionPlan extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscription_plans';

    /**
     * The attributes that are translatable.
     *
     * @var list<string>
     */
    public array $translatable = [
        'name',
        'description',
        'features',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_egp' => 'decimal:2',
            'price_usd' => 'decimal:2',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Format price in EGP currency with symbol.
     */
    public function getFormattedPriceEgpAttribute(): string
    {
        return number_format((float) $this->price_egp, 0).' ج.م';
    }

    /**
     * Format price in USD currency with symbol.
     */
    public function getFormattedPriceUsdAttribute(): string
    {
        return '$'.number_format((float) $this->price_usd, 0);
    }
}

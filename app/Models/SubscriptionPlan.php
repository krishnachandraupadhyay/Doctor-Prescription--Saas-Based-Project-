<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Enums\PlanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'trial_days',
        'status',
        'limits',
        'features',
        'is_popular',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'billing_cycle' => BillingCycle::class,
            'status' => PlanStatus::class,
            'trial_days' => 'integer',
            'limits' => 'array',
            'features' => 'array',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'subscription_plan_id');
    }

    public function hasFeature(string $featureCode): bool
    {
        $features = $this->features ?? [];
        return in_array($featureCode, $features, true);
    }

    public function getLimit(string $limitKey, int $default = -1): int
    {
        $limits = $this->limits ?? [];
        if (!isset($limits[$limitKey]) || $limits[$limitKey] === null || $limits[$limitKey] === '') {
            return $default;
        }

        return (int) $limits[$limitKey];
    }

    public function isUnlimited(string $limitKey): bool
    {
        return $this->getLimit($limitKey) === -1;
    }

    public function displayLimit(string $limitKey): string
    {
        $limit = $this->getLimit($limitKey);
        return $limit === -1 ? 'Unlimited' : number_format($limit);
    }

    public function formattedPrice(): string
    {
        if ((float) $this->price === 0.0) {
            return 'Free';
        }

        return '₹' . number_format((float) $this->price, 2);
    }

    public function isActive(): bool
    {
        return $this->status === PlanStatus::ACTIVE;
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}

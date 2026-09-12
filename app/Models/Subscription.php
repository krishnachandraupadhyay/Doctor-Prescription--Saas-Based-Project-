<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscriptions';

    protected $fillable = [
        'subscriber_type',
        'subscriber_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'trial_ends_at',
        'status',
        'payment_reference',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'trial_ends_at' => 'date',
            'status' => SubscriptionStatus::class,
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Get the owning subscriber model (Doctor or Clinic).
     */
    public function subscriber()
    {
        if ($this->subscriber_type === 'clinic') {
            return $this->belongsTo(Clinic::class, 'subscriber_id');
        }

        return $this->belongsTo(Doctor::class, 'subscriber_id');
    }

    public function isOperable(): bool
    {
        if ($this->effectiveStatus()->isOperable()) {
            return true;
        }

        return false;
    }

    public function isActive(): bool
    {
        return $this->effectiveStatus() === SubscriptionStatus::ACTIVE;
    }

    public function isTrial(): bool
    {
        if ($this->status !== SubscriptionStatus::TRIAL) {
            return false;
        }

        if (!$this->trial_ends_at) {
            return false;
        }

        $today = Carbon::today();
        return $this->start_date <= $today && $this->trial_ends_at >= $today;
    }

    public function isExpired(): bool
    {
        if ($this->status === SubscriptionStatus::EXPIRED) {
            return true;
        }

        if ($this->status->isOperable() && $this->end_date < Carbon::today()) {
            return true;
        }

        return false;
    }

    public function effectiveStatus(): SubscriptionStatus
    {
        if ($this->status->isOperable() && $this->end_date < Carbon::today()) {
            return SubscriptionStatus::EXPIRED;
        }

        return $this->status;
    }

    public function effectiveStatusLabel(): string
    {
        return $this->effectiveStatus()->label();
    }

    public function effectiveBadgeClasses(): string
    {
        return $this->effectiveStatus()->badgeClasses();
    }

    public function daysRemaining(): int
    {
        $today = Carbon::today();
        if ($this->end_date < $today) {
            return 0;
        }

        return (int) $today->diffInDays($this->end_date, false);
    }
}

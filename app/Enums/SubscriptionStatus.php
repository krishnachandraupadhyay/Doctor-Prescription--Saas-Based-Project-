<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case TRIAL = 'trial';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::TRIAL => 'Trial Period',
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::TRIAL => 'bg-info-subtle text-info border border-info-subtle',
            self::ACTIVE => 'bg-success-subtle text-success border border-success-subtle',
            self::EXPIRED => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
            self::CANCELLED => 'bg-danger-subtle text-danger border border-danger-subtle',
            self::SUSPENDED => 'bg-warning-subtle text-warning border border-warning-subtle',
        };
    }

    public function isOperable(): bool
    {
        return in_array($this, [self::ACTIVE, self::TRIAL], true);
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isTrial(): bool
    {
        return $this === self::TRIAL;
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function isSuspended(): bool
    {
        return $this === self::SUSPENDED;
    }
}

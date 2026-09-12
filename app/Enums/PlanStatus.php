<?php

namespace App\Enums;

enum PlanStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-success-subtle text-success',
            self::INACTIVE => 'bg-danger-subtle text-danger',
        };
    }
}

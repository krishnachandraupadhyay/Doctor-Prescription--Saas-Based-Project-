<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'clinic_code',
        'prescription_type',
        'has_member',
        'has_payment_category',
        'has_deleted_staff',
        'has_revisit_rule',
        'revisit_validity_days',
        'revisit_fee_type',
        'revisit_discount_percent',
        'status',
        'verified',
        'verified_by',
        'verified_at',
        'isdeleted',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password'                 => 'hashed',
        'has_member'               => 'boolean',
        'has_payment_category'     => 'boolean',
        'has_deleted_staff'        => 'boolean',
        'has_revisit_rule'         => 'boolean',
        'revisit_validity_days'    => 'integer',
        'revisit_discount_percent' => 'integer',
        'status'                   => 'boolean',
        'verified'                 => 'boolean',
        'verified_at'              => 'datetime',
        'isdeleted'                => 'boolean',
    ];

    /**
     * Check if a patient's visit qualifies for free revisit under this clinic's policy.
     */
    public function isRevisitFree($lastVisitDate)
    {
        if (!$this->has_revisit_rule || empty($lastVisitDate)) {
            return false;
        }

        if ($this->revisit_fee_type !== 'free') {
            return false;
        }

        $validityDays = $this->revisit_validity_days ?? 7;
        $daysAgo = \Carbon\Carbon::parse($lastVisitDate)->startOfDay()->diffInDays(now()->startOfDay());

        return $daysAgo <= $validityDays;
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'clinic_id');
    }

    public function paymentCategories()
    {
        return $this->hasMany(PaymentCategory::class, 'clinic_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class, 'subscriber_id')
            ->where('subscriber_type', 'clinic');
    }

    public function currentSubscription(): ?\App\Models\Subscription
    {
        return $this->subscriptions()
            ->with('plan')
            ->whereIn('status', ['active', 'trial'])
            ->where('end_date', '>=', now()->toDateString())
            ->latest('id')
            ->first();
    }
}


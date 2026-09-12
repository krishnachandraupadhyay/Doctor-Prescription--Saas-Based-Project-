<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Member;
use App\Models\presciption_data;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionService
{
    /**
     * Resolve the active Clinic subscriber for current auth context.
     */
    public static function resolveSubscriber(): ?Clinic
    {
        if (Auth::guard('clinic')->check()) {
            return Auth::guard('clinic')->user();
        }

        if (Auth::guard('doctor')->check()) {
            $doctor = Auth::guard('doctor')->user();
            if ($doctor && $doctor->clinic_id) {
                return $doctor->clinic ?: Clinic::find($doctor->clinic_id);
            }
            return null;
        }

        if (Auth::guard('member')->check()) {
            $member = Auth::guard('member')->user();
            if ($member) {
                if ($member->clinic_id) {
                    return Clinic::find($member->clinic_id);
                }
                if ($member->doctor_id) {
                    $doc = Doctor::find($member->doctor_id);
                    if ($doc && $doc->clinic_id) {
                        return $doc->clinic ?: Clinic::find($doc->clinic_id);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get the active subscription for the current context or given clinic.
     */
    public static function getActiveSubscription(Clinic|null $subscriber = null): ?Subscription
    {
        $entity = $subscriber ?: self::resolveSubscriber();
        if (!$entity) {
            return null;
        }

        return $entity->currentSubscription();
    }

    /**
     * Check if current clinic has an operable subscription.
     */
    public static function hasActiveSubscription(Clinic|null $subscriber = null): bool
    {
        $sub = self::getActiveSubscription($subscriber);
        return $sub !== null && $sub->isOperable();
    }

    /**
     * Check if a specific feature code is enabled in the active subscription plan.
     */
    public static function hasFeature(string $featureCode, Clinic|null $subscriber = null): bool
    {
        // Super Admin has all features
        if (Auth::guard('web')->check() && Auth::user()->role === 'admin') {
            return true;
        }

        $sub = self::getActiveSubscription($subscriber);
        if (!$sub || !$sub->plan) {
            return false;
        }

        return $sub->plan->hasFeature($featureCode);
    }

    /**
     * Get numeric limit from active plan (-1 represents unlimited).
     */
    public static function getLimit(string $limitKey, int $default = -1, Clinic|null $subscriber = null): int
    {
        $sub = self::getActiveSubscription($subscriber);
        if (!$sub || !$sub->plan) {
            return 0;
        }

        return $sub->plan->getLimit($limitKey, $default);
    }

    /**
     * Check if adding a new patient is within quota limit for the clinic.
     */
    public static function canAddPatient(Clinic|null $subscriber = null): bool
    {
        $clinic = $subscriber ?: self::resolveSubscriber();
        if (!$clinic) {
            return true;
        }

        $limit = self::getLimit('max_patients_per_month', -1, $clinic);
        if ($limit === -1) {
            return true;
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id');
        $count = Patient::whereIn('doctor_id', $doctorIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $count < $limit;
    }

    /**
     * Check if creating a new prescription is within quota limit for the clinic.
     */
    public static function canAddPrescription(Clinic|null $subscriber = null): bool
    {
        $clinic = $subscriber ?: self::resolveSubscriber();
        if (!$clinic) {
            return true;
        }

        $limit = self::getLimit('max_prescriptions_per_month', -1, $clinic);
        if ($limit === -1) {
            return true;
        }

        $doctorIds = Doctor::where('clinic_id', $clinic->id)->pluck('id');
        $count = presciption_data::whereIn('doctor_id', $doctorIds)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $count < $limit;
    }

    /**
     * Check if adding new staff member is within quota limit for the clinic.
     */
    public static function canAddStaff(Clinic|null $subscriber = null): bool
    {
        $clinic = $subscriber ?: self::resolveSubscriber();
        if (!$clinic) {
            return true;
        }

        $limit = self::getLimit('max_staff', -1, $clinic);
        if ($limit === -1) {
            return true;
        }

        $count = Member::where('clinic_id', $clinic->id)->where('isdeleted', 0)->count();
        return $count < $limit;
    }

    /**
     * Check if clinic can add more doctors.
     */
    public static function canAddDoctor(Clinic|null $clinic = null): bool
    {
        $clinic = $clinic ?: self::resolveSubscriber();
        if (!$clinic) {
            return true;
        }

        $limit = self::getLimit('max_doctors', -1, $clinic);
        if ($limit === -1) {
            return true;
        }

        $count = Doctor::where('clinic_id', $clinic->id)->where('isdeleted', 0)->count();
        return $count < $limit;
    }
}

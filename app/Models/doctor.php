<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    protected $fillable = [
        'Doctor_Emp_id',
        'clinic_id',
        'name',
        'email',
        'password',
        'qualification',
        'specialization',
        'registration_number',
        'license_number',
        'clinic_name',
        'Experience',
        'phone',
        'signature',
        'prescription_type',
        'profile_completed',
        'verified',
        'status',
        'has_payment_category',
        'has_deleted_staff',
        'isdeleted',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Automatically strip prefix "Dr." / "Dr " / "Doctor " if entered,
     * so that doctor names are stored clean and views display "Dr. {Name}" without duplication.
     */
    public function setNameAttribute($value)
    {
        $clean = preg_replace('/^(dr\.?|doctor)\s+/i', '', trim($value ?? ''));
        $this->attributes['name'] = $clean;
    }

    public function clinic()
    {
        return $this->belongsTo(\App\Models\Clinic::class, 'clinic_id');
    }
    // app/Models/Doctor.php
    public function paymentCategories()
    {
        return $this->hasMany(\App\Models\PaymentCategory::class, 'doctor_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id')
                    ->where('role', 'onboarding');
    }

    public function photoDocument()
    {
        return $this->hasOne(\App\Models\doctor_document::class, 'doctor_id')->where('document_type', 'photo');
    }

    public function clinicDocuments()
    {
        return $this->hasOne(\App\Models\doctor_clinic_document::class, 'doctor_id');
    }

    public function doctor_clinic_documents()
    {
        return $this->hasOne(\App\Models\doctor_clinic_document::class, 'doctor_id');
    }

    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'member_id');
    }

    public function updaterUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'member_id');
    }

    public function getCreatorNameAttribute()
    {
        if (empty($this->created_by)) {
            return 'Admin';
        }
        if (strtolower($this->created_by) === 'admin') {
            return 'Super Admin';
        }
        $user = \App\Models\User::where('member_id', $this->created_by)
            ->orWhere('id', $this->created_by)
            ->orWhere('name', $this->created_by)
            ->first();

        return $user ? $user->name : $this->created_by;
    }

    public function getUpdaterNameAttribute()
    {
        if (empty($this->updated_by)) {
            return 'Admin';
        }
        if (strtolower($this->updated_by) === 'admin') {
            return 'Super Admin';
        }
        $user = \App\Models\User::where('member_id', $this->updated_by)
            ->orWhere('id', $this->updated_by)
            ->orWhere('name', $this->updated_by)
            ->first();

        return $user ? $user->name : $this->updated_by;
    }

    /**
     * Get initials / prefix from hospital or clinic name.
     * E.g., "Prachi Hospital" => "PH", "City Heart Clinic" => "CHC", "Apollo" => "AP"
     */
    public static function getHospitalPrefix(?string $clinicName): string
    {
        if (empty($clinicName)) {
            return 'GEN';
        }

        $words = preg_split('/[\s\-_]+/', trim($clinicName));
        $words = array_values(array_filter($words));

        if (count($words) >= 2) {
            $prefix = '';
            foreach ($words as $w) {
                $firstChar = mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $w), 0, 1);
                if ($firstChar !== '') {
                    $prefix .= $firstChar;
                }
            }
        } else {
            $clean = preg_replace('/[^A-Za-z0-9]/', '', $clinicName);
            $prefix = mb_substr($clean, 0, 2);
        }

        $prefix = strtoupper($prefix);
        return !empty($prefix) ? $prefix : 'GEN';
    }

    /**
     * Generate unique Doctor Emp ID formatted as DOC-{HospitalPrefix}-{Number}
     * E.g. DOC-PH-0001
     */
    public static function generateDoctorId(?string $clinicName): string
    {
        $prefix = self::getHospitalPrefix($clinicName);
        $searchPattern = 'DOC-' . $prefix . '-%';

        $lastDoctor = self::where('Doctor_Emp_id', 'LIKE', $searchPattern)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastDoctor && $lastDoctor->Doctor_Emp_id) {
            $parts = explode('-', $lastDoctor->Doctor_Emp_id);
            $lastPart = end($parts);
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastPart);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return 'DOC-' . $prefix . '-' . $nextNumber;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'profile_completed' => 'boolean',
            'status' => 'boolean',
            'has_payment_category' => 'boolean',
            'isdeleted' => 'boolean',
        ];
    }

    public function currentSubscription(): ?\App\Models\Subscription
    {
        if ($this->clinic_id && $this->clinic) {
            return $this->clinic->currentSubscription();
        }

        return null;
    }
}
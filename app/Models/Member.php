<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'doctor_id',
        'member_id',
        'name',
        'email',
        'password',
        'role',
        'status',
        'verified',
        'verified_by',
        'verified_at',
        'isdeleted',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // Password ko list/array me accidentally expose hone se rokta hai
    protected $hidden = [
        'password',
    ];

    /**
     * Associated Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Resolve the associated Clinic for this staff member
     */
    public function clinic(): ?Clinic
    {
        if ($this->doctor) {
            if ($this->doctor->clinic) {
                return $this->doctor->clinic;
            }
            if ($this->doctor->clinic_id) {
                return Clinic::find($this->doctor->clinic_id);
            }
            if (!empty($this->doctor->clinic_name)) {
                return Clinic::where('name', $this->doctor->clinic_name)->first();
            }
        }

        if (!empty($this->created_by)) {
            // First check if created_by is a doctor
            $doc = Doctor::where('name', $this->created_by)->orWhere('id', $this->created_by)->first();
            if ($doc) {
                if ($doc->clinic) {
                    return $doc->clinic;
                }
                if ($doc->clinic_id) {
                    return Clinic::find($doc->clinic_id);
                }
            }

            // Next check if created_by is directly a clinic
            return Clinic::where('name', $this->created_by)->orWhere('id', $this->created_by)->first();
        }

        return null;
    }

    /**
     * Get the active subscription inherited from the clinic
     */
    public function currentSubscription(): ?\App\Models\Subscription
    {
        return $this->clinic()?->currentSubscription();
    }
}
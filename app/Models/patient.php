<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    //
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function presciption_data()
{
    return $this->hasMany(\App\Models\presciption_data::class, 'patient_id');
}
    
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class, 'payment_category_id');
    }

    protected $fillable=[
            'doctor_id',
            'member_id',
            'payment_category_id',
            'patient_id',
            'registration',
            'patient_name',
            'guardian_type',
            'husband_father_name',
            'age_year',
            'age_month',
            'mobile',
            'gender',
            'Registration_date',
            'address',
            'aaddhar_num',
            'status',
            'created_by',
            'updated_by'
    ];
    protected $hidden=[];

    public function getIsCompletedAttribute()
    {
        // Check if doctor has completed a prescription FOR TODAY with symptoms or medicines
        $hasTodayPrescription = $this->presciption_data()
            ->whereDate('created_at', today())
            ->where(function($q) {
                $q->whereNotNull('symptoms')
                  ->orWhereNotNull('medicine_id');
            })->exists();

        if ($hasTodayPrescription) {
            return true;
        }

        // If status is completed and patient was created today and has any prescription data for today
        if (strtolower((string)$this->status) === 'completed' && $this->updated_at && $this->updated_at->isToday() && $hasTodayPrescription) {
            return true;
        }

        return false;
    }

    public function getHasTodayVitalsAttribute()
    {
        return $this->presciption_data()
            ->whereDate('created_at', today())
            ->whereNotNull('blood_pressure')
            ->exists();
    }

    public function getIsRegisteredForTodayAttribute()
    {
        return ($this->created_at && $this->created_at->isToday())
            || ($this->updated_at && $this->updated_at->isToday() && strtolower((string)$this->status) === 'waiting')
            || $this->presciption_data()->whereDate('created_at', today())->exists();
    }

    public function getCanStaffAddVitalsTodayAttribute()
    {
        // Check if receptionist registered patient for today
        $registeredToday = ($this->created_at && $this->created_at->isToday())
            || ($this->updated_at && $this->updated_at->isToday() && strtolower((string)$this->status) === 'waiting');

        if (!$registeredToday) {
            return false;
        }

        // Check if staff has already filled physical exam for today
        $alreadyFilled = $this->presciption_data()
            ->whereDate('created_at', today())
            ->whereNotNull('blood_pressure')
            ->whereNotNull('pulse_rate')
            ->exists();

        return !$alreadyFilled;
    }

}

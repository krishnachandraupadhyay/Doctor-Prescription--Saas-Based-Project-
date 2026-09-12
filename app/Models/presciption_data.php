<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presciption_data extends Model
{
    //
    use HasFactory;
    protected $table = 'presciption_data';
  public function medicine()
{
    return $this->belongsTo(\App\Models\Medicine::class, 'medicine_id');
}

public function dosageName()
{
    return $this->belongsTo(\App\Models\dosage_name::class, 'dosage');
}

public function unitName()
{
    return $this->belongsTo(\App\Models\unit_name::class, 'unit');
}

public function intervalName()
{
    return $this->belongsTo(\App\Models\interval_name::class, 'frequency');
}

public function durationName()
{
    return $this->belongsTo(\App\Models\duration_name::class, 'duration');
}
public function patient()
{
    return $this->belongsTo(\App\Models\patient::class, 'patient_id');
}

public function doctor()
{
    return $this->belongsTo(\App\Models\Doctor::class, 'Doctor_Emp_id');
}

public function paymentCategory()
{
    return $this->belongsTo(\App\Models\PaymentCategory::class, 'payment_category_id');
}

public function getAdviceNamesAttribute()
{
    if (!$this->advice) {
        return [];
    }
    $ids = array_filter(explode(',', $this->advice));
    return \App\Models\Suggestion::whereIn('id', $ids)->pluck('suggestion_name')->toArray();
}
    protected $fillable = [
            'Doctor_Emp_id',
            'patient_id',
            'payment_category_id',
            'weight',
            'height',
            'blood_pressure',
            'pulse_rate',
            'temperature',
            'blood_groups',
            'spo2',
            'sugar',
            'diagnosis_test',
            'symptoms',
            'diagnosis',
            'medicine_id',
            'dosage',
            'unit',
            'frequency',
            'duration',
            'advice',
            'followup',
            'next_visit_date'
    ];
}

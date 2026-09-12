<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class doctor_clinic_document extends Model
{
    protected $table = 'doctor_clinic_documents';

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'member_id',
        'status',
        'created_by',
        'updated_by',
        'photo',
        'doctor_sign',
        'clinic_stamp',
        'header',
        'footer',
    ];

    public function clinic()
    {
        return $this->belongsTo(\App\Models\Clinic::class, 'clinic_id');
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\doctor::class, 'doctor_id');
    }
}
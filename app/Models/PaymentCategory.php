<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'doctor_id',
        'clinic_id',
        'price',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Ye category kis clinic ke liye hai.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Ye category kis doctor ke liye hai.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Patients registered under this payment category.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class, 'payment_category_id');
    }
}


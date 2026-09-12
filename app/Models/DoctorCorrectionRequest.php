<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCorrectionRequest extends Model
{
    use HasFactory;

    protected $table = 'doctor_correction_requests';

    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'field_name',
        'current_value',
        'requested_value',
        'reason',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'is_read',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'is_read'     => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Doctor who submitted the request.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Clinic associated with this request.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    /**
     * Formatted field label for UI display.
     */
    public function getFieldLabelAttribute(): string
    {
        $labels = [
            'name'                => 'Full Name',
            'phone'               => 'Phone Number',
            'email'               => 'Email Address',
            'specialization'      => 'Specialization',
            'qualification'       => 'Qualification',
            'registration_number' => 'Registration / Medical License No.',
            'experience'          => 'Years of Experience',
            'clinic_name'         => 'Clinic Name',
            'clinic_address'      => 'Clinic Address',
            'other'               => 'Other / General Information',
        ];

        return $labels[$this->field_name] ?? ucwords(str_replace('_', ' ', $this->field_name));
    }
}

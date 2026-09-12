<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class symptoms extends Model
{
    use HasFactory;

    protected $table = 'symptoms';

    protected $fillable = [
        'symptom_code',
        'symptom_name',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Generate unique Symptom Code formatted as SYM-XXXXX (e.g. SYM-00001)
     */
    public static function generateSymptomCode(): string
    {
        $lastSymptom = self::whereNotNull('symptom_code')
            ->where('symptom_code', 'LIKE', 'SYM-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSymptom && $lastSymptom->symptom_code) {
            $num = (int) preg_replace('/[^0-9]/', '', $lastSymptom->symptom_code);
            $nextNum = $num + 1;
        } else {
            $maxId = (int) self::max('id');
            $nextNum = $maxId + 1;
        }

        do {
            $code = 'SYM-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            $nextNum++;
        } while (self::where('symptom_code', $code)->exists());

        return $code;
    }

    /**
     * Auto-generate symptom code on creating if not supplied.
     */
    protected static function booted()
    {
        static::creating(function ($symptom) {
            if (empty($symptom->symptom_code)) {
                $symptom->symptom_code = self::generateSymptomCode();
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class diagnosis_test extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_tests';

    protected $fillable = [
        'test_code',
        'test_name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Generate unique Test Code formatted as DX-XXXXX (e.g. DX-00001)
     */
    public static function generateTestCode(): string
    {
        $lastTest = self::whereNotNull('test_code')
            ->where('test_code', 'LIKE', 'DX-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTest && $lastTest->test_code) {
            $num = (int) preg_replace('/[^0-9]/', '', $lastTest->test_code);
            $nextNum = $num + 1;
        } else {
            $maxId = (int) self::max('id');
            $nextNum = $maxId + 1;
        }

        do {
            $code = 'DX-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            $nextNum++;
        } while (self::where('test_code', $code)->exists());

        return $code;
    }

    /**
     * Auto-generate test code on creating if not supplied.
     */
    protected static function booted()
    {
        static::creating(function ($test) {
            if (empty($test->test_code)) {
                $test->test_code = self::generateTestCode();
            }
        });
    }
}
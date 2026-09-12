<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class suggestion extends Model
{
    use HasFactory;

    protected $table = 'suggestion';

    protected $fillable = [
        'suggestion_code',
        'suggestion_name',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Generate unique Suggestion Code formatted as SUG-XXXXX (e.g. SUG-00001)
     */
    public static function generateSuggestionCode(): string
    {
        $lastSug = self::whereNotNull('suggestion_code')
            ->where('suggestion_code', 'LIKE', 'SUG-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSug && $lastSug->suggestion_code) {
            $num = (int) preg_replace('/[^0-9]/', '', $lastSug->suggestion_code);
            $nextNum = $num + 1;
        } else {
            $maxId = (int) self::max('id');
            $nextNum = $maxId + 1;
        }

        do {
            $code = 'SUG-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            $nextNum++;
        } while (self::where('suggestion_code', $code)->exists());

        return $code;
    }

    /**
     * Auto-generate suggestion code on creating if not supplied.
     */
    protected static function booted()
    {
        static::creating(function ($sug) {
            if (empty($sug->suggestion_code)) {
                $sug->suggestion_code = self::generateSuggestionCode();
            }
        });
    }
}

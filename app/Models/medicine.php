<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class medicine extends Model
{
    use HasFactory;

    protected $table = 'medicine';

    protected $fillable = [
        'medicine_code',
        'medicine_name',
        'generic_name',
        'category_id',
        'company_id',
        'description',
        'created_by',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(company_name::class, 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(medicine_categorie::class, 'category_id');
    }

    /**
     * Generate unique Medicine Code formatted as MED-XXXXX (e.g. MED-00001)
     */
    public static function generateMedicineCode(): string
    {
        $lastMed = self::whereNotNull('medicine_code')
            ->where('medicine_code', 'LIKE', 'MED-%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastMed && $lastMed->medicine_code) {
            $num = (int) preg_replace('/[^0-9]/', '', $lastMed->medicine_code);
            $nextNum = $num + 1;
        } else {
            $maxId = (int) self::max('id');
            $nextNum = $maxId + 1;
        }

        do {
            $code = 'MED-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
            $nextNum++;
        } while (self::where('medicine_code', $code)->exists());

        return $code;
    }

    /**
     * Auto-generate medicine code on creating if not supplied.
     */
    protected static function booted()
    {
        static::creating(function ($med) {
            if (empty($med->medicine_code)) {
                $med->medicine_code = self::generateMedicineCode();
            }
        });
    }
}

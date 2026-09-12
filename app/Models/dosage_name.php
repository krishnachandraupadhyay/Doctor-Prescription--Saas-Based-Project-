<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class dosage_name extends Model
{
     use HasFactory;
    protected $table = 'dosage_name';

    protected $fillable = [
        'category_id',
        'dosage_name',
        'unit_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    //
}

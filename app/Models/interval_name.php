<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class interval_name extends Model
{
    //
     use HasFactory;
     protected $fillable = [
        'interval_name',
        'unit_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}

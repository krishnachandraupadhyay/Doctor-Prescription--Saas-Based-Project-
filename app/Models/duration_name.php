<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class duration_name extends Model
{
       use HasFactory;

    protected $fillable = [
        'duration_name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    //
}

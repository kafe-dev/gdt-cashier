<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paygate extends Model
{
    use HasFactory;

    const STAUTS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $fillable = [
        'name',
        'url',
        'api_data',
        'vps_data',
        'type',
        'status',
        'limitation',
        'mode',
    ];

    protected $casts = [
        'api_data' => 'array',
        'vps_data' => 'array',

    ];
}

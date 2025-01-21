<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paygate extends Model
{
    use HasFactory;

    const STAUTS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const TYPE_PAYPAL = 0;
    const TYPE_STRIPE = 1;

    const TYPE = [
        self::TYPE_PAYPAL => 'Paypal',
        self::TYPE_STRIPE => 'Strpe',
    ];


    const STATUS = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STAUTS_ACTIVE => 'Active'
    ];

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

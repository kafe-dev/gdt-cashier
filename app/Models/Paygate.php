<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Paygate model.
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $api_data
 * @property string $vps_data
 * @property int $type
 * @property int $status
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property float $limitation
 * @property int $mode
 */
class Paygate extends Model
{
    use HasFactory;

    public final const TYPE_PAYPAL = 0;

    public final const TYPE_STRIPE = 1;

    public final const TYPE = [
        self::TYPE_PAYPAL => 'Paypal',
        self::TYPE_STRIPE => 'Stripe',
    ];

    public final const STATUS_INACTIVE = 0;

    public final const STATUS_ACTIVE = 1;

    public final const STATUS_DRAFT = 2;

    public final const STATUS = [
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_DRAFT => 'Draft',
    ];

    public final const MODE_SANDBOX = 0;

    public final const MODE_LIVE = 1;

    public final const MODES = [
        self::MODE_SANDBOX => 'Sandbox',
        self::MODE_LIVE => 'Live',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'paygates';

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'api_data' => 'array',
        'vps_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

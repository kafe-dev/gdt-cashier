<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OrderTracking model.
 *
 * @property int $id
 * @property int|null $order_id
 * @property string $tracking_number
 * @property string|null $courier
 * @property int $status
 * @property string|null $tracking_data
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class OrderTracking extends Model
{
    use HasFactory;

    public final const int STATUS_IN_TRANSIT = 0;

    public final const int STATUS_DELIVERED = 1;

    public final const int STATUS_FAILED = 2;

    public final const array STATUSES = [
        self::STATUS_IN_TRANSIT => 'In Transit',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_FAILED => 'Failed',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'order_tracking';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'tracking_number',
        'courier',
        'tracking_data',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

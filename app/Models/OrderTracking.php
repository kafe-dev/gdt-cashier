<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * OrderTracking model.
 *
 * @property int $id
 * @property string|null $paygate_id
 * @property string|null $paygate_name
 * @property string|null $invoice_number
 * @property string|null $transaction_id
 * @property string|null $tracking_number
 * @property string|null $courier_code
 * @property string|null $tracking_status
 * @property string|null $tracking_data
 * @property int $type
 * @property bool $has_tracking_number
 * @property mixed|null $ordered_at
 * @property mixed|null $closed_at
 * @property mixed|null $last_checked_at
 * @property mixed|null $exported_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class OrderTracking extends Model
{

    use HasFactory;

    final public const int TYPE_OPEN = 0;

    final public const int TYPE_CLOSED = 1;

    final public const array TYPES = [
        self::TYPE_OPEN   => 'Open',
        self::TYPE_CLOSED => 'Closed',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'order_tracking';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'paygate_id',
        'paygate_name',
        'invoice_number',
        'transaction_id',
        'tracking_number',
        'courier_code',
        'tracking_status',
        'tracking_data',
        'type',
        'has_tracking_number',
        'ordered_at',
        'closed_at',
        'last_checked_at',
        'exported_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'ordered_at'      => 'datetime',
            'closed_at'       => 'datetime',
            'last_checked_at' => 'datetime',
            'exported_at'     => 'datetime',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

}

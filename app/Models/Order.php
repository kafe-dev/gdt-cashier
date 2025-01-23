<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Order model.
 *
 * @property int $id
 * @property string $code
 * @property string $status
 * @property string $invoicer_email_address
 * @property string|null $billing_info
 * @property float $amount
 * @property string $currency_code
 * @property float|null paid_amount
 * @property string|null $paid_currency_code
 * @property string|null $link
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Order extends Model
{
    public final const string STATUS_NEW = 'NEW';

    public final const string STATUS_PAID = 'PAID';

    public final const array STATUSES = [
        self::STATUS_NEW => 'NEW',
        self::STATUS_PAID => 'PAID',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'orders';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'status',
        'invoicer_email_address',
        'billing_info',
        'amount',
        'currency_code',
        'paid_amount',
        'paid_currency_code',
        'link',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'billing_info' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

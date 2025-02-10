<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 09/02/2025
 * @time 16:39
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PayPalTransaction
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $time
 * @property string|null $timezone
 * @property string|null $name
 * @property string|null $type
 * @property string|null $status
 * @property string|null $currency
 * @property string|null $gross
 * @property string|null $fee
 * @property string|null $net
 * @property string|null $from_email_address
 * @property string|null $to_email_address
 * @property string|null $transaction_id
 * @property string|null $shipping_address
 * @property string|null $address_status
 * @property string|null $item_title
 * @property string|null $item_id
 * @property string|null $shipping_and_handling_amount
 * @property string|null $insurance_amount
 * @property string|null $sales_tax
 * @property string|null $option_1_name
 * @property string|null $option_1_value
 * @property string|null $option_2_name
 * @property string|null $option_2_value
 * @property string|null $reference_txn_id
 * @property string|null $invoice_number
 * @property string|null $custom_number
 * @property string|null $quantity
 * @property string|null $receipt_id
 * @property string|null $balance
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $town_city
 * @property string|null $state_province
 * @property string|null $zip_postal_code
 * @property string|null $country
 * @property string|null $contact_phone_number
 * @property string|null $subject
 * @property string|null $note
 * @property string|null $country_code
 * @property string|null $balance_impact
 * @property mixed|null closed_at
 * @property mixed|null last_checked_at
 * @property mixed|null exported_at
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class PaypalTransaction extends Model
{
    use HasFactory;

    protected $table = 'paypal_transactions';

    public $timestamps = true;

    protected $fillable = [
        'date',
        'time',
        'timezone',
        'name',
        'type',
        'status',
        'currency',
        'gross',
        'fee',
        'net',
        'from_email_address',
        'to_email_address',
        'transaction_id',
        'shipping_address',
        'address_status',
        'item_title',
        'item_id',
        'shipping_and_handling_amount',
        'insurance_amount',
        'sales_tax',
        'option_1_name',
        'option_1_value',
        'option_2_name',
        'option_2_value',
        'reference_txn_id',
        'invoice_number',
        'custom_number',
        'quantity',
        'receipt_id',
        'balance',
        'address_line_1',
        'address_line_2',
        'town_city',
        'state_province',
        'zip_postal_code',
        'country',
        'contact_phone_number',
        'subject',
        'note',
        'country_code',
        'balance_impact',
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
            'closed_at' => 'datetime',
            'last_checked_at' => 'datetime',
            'exported_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

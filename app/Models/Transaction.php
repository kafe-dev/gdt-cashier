<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property string $transaction_id              Mã giao dịch
 * @property string $transaction_event_code      Mã sự kiện giao dịch
 * @property \Illuminate\Support\Carbon|null $transaction_initiation_date  Thời gian bắt đầu giao dịch
 * @property \Illuminate\Support\Carbon|null $transaction_updated_date    Thời gian cập nhật giao dịch
 * @property string $transaction_amount_currency  Đơn vị tiền tệ của giao dịch
 * @property float $transaction_amount_value      Giá trị của giao dịch
 * @property string $transaction_status           Trạng thái của giao dịch
 * @property string $transaction_subject          Chủ đề của giao dịch
 * @property string $ending_balance_currency       Đơn vị tiền tệ của số dư cuối cùng
 * @property float $ending_balance_value           Giá trị của số dư cuối cùng
 * @property string $available_balance_currency    Đơn vị tiền tệ của số dư khả dụng
 * @property float $available_balance_value        Giá trị của số dư khả dụng
 * @property string $protection_eligibility        Trạng thái bảo vệ của giao dịch
 * @property array $payer_info                      Thông tin người thanh toán
 * @property array $shipping_info                   Thông tin vận chuyển
 * @property array $cart_info                       Thông tin giỏ hàng
 * @property array $store_info                       Thông tin cửa hàng
 * @property array $incentive_info                   Thông tin khuyến mại
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'transaction_event_code',
        'transaction_initiation_date',
        'transaction_updated_date',
        'transaction_amount_currency',
        'transaction_amount_value',
        'transaction_status',
        'transaction_subject',
        'ending_balance_currency',
        'ending_balance_value',
        'available_balance_currency',
        'available_balance_value',
        'protection_eligibility',
        'payer_info',
        'shipping_info',
        'cart_info',
        'store_info',
        'incentive_info',
    ];

//    protected $casts = [
//        'transaction_initiation_date' => 'datetime',
//        'transaction_updated_date' => 'datetime',
//        'transaction_amount_value' => 'decimal:2',
//        'ending_balance_value' => 'decimal:2',
//        'available_balance_value' => 'decimal:2',
//        'payer_info' => 'array',
//        'shipping_info' => 'array',
//        'cart_info' => 'array',
//        'store_info' => 'array',
//        'incentive_info' => 'array',
//    ];
}

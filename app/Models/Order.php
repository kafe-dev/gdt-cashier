<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu không theo quy tắc Laravel (số nhiều của tên model)
    protected $table = 'orders';

    // Các thuộc tính có thể gán đại trà
    protected $fillable = [
        'code',
        'status',
        'invoicer_email_address',
        'billing_info',
        'amount',
        'currency_code',
        'paid_amount',
        'paid_currency_code',
        'link'
    ];

    // Định nghĩa các trường có kiểu dữ liệu JSON (sẽ tự động được chuyển đổi thành mảng)
    protected $casts = [
        'billing_info' => 'array',
    ];

    // Đặt thuộc tính $dates nếu có trường ngày tháng cần chuyển thành Carbon instance (nếu có)
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}

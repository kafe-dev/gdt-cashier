<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaygateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 50 bản ghi giả cho bảng paygates
        \App\Models\Paygate::factory(100)->create();
    }
}

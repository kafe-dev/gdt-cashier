<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paygate>
 */
class PaygateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,  // Tạo tên giả cho Paygate
            'url' => $this->faker->url,  // Tạo URL giả
            'api_data' => $this->faker->text,  // Dữ liệu API giả
            'vps_data' => $this->faker->text,  // Dữ liệu VPS giả
            'type' => $this->faker->randomElement([0, 1]),  // Loại (ví dụ: "payment", "withdrawal", ...)
            'status' => $this->faker->randomElement([0, 1]),  // Trạng thái
            'limitation' => $this->faker->randomFloat(2, 0, 999999999999999999999999999999999999999999999999999999999999999),  // Giới hạn (ví dụ: "Chỉ áp dụng cho các giao dịch dưới 100$")
            'mode' => $this->faker->randomElement([0, 1]),  // Chế độ (sandbox hoặc production)
            'created_at'=>time(), //
            'updated_at'=>time(), //
        ];
    }
}

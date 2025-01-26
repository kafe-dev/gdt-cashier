<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paygate>
 */
class OrderTrackingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => 1,
            'tracking_number' => 'YT'.$this->faker->numberBetween(1000000000000000, 9999999999999999),
            'courier' => 'YunExpress',
            'status' => $this->faker->randomElement([0, 1, 2]),
        ];
    }
}

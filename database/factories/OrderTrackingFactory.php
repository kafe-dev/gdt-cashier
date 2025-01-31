<?php

namespace Database\Factories;

use App\Models\OrderTracking;
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
            'order_id' => $this->faker->numberBetween(1, 10),
            'type' => OrderTracking::TYPE_OPEN,
        ];
    }
}

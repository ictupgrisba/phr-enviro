<?php

namespace Database\Factories;

use App\Utils\VehicleClassEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plat' => fake()->name,
            'type' => VehicleClassEnum::HEAVY_VEHICLE_TYPE->value,
            'vendor' => fake()->name,
            'operator_id' => null
        ];
    }
}

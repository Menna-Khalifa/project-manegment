<?php

namespace Database\Factories;

use App\Models\ProjectCapacity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectCapacityFactory extends Factory
{
    protected $model = ProjectCapacity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['5 kW', '10 kW', '15 kW', '20 kW', '30 kW', '50 kW', '100 kW']),
        ];
    }
}
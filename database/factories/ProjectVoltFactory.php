<?php

namespace Database\Factories;

use App\Models\ProjectVolt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectVoltFactory extends Factory
{
    protected $model = ProjectVolt::class;

    public function definition(): array
    {
        return [
            'value' => $this->faker->randomElement(['110V', '220V', '380V', '440V']),
        ];
    }
}
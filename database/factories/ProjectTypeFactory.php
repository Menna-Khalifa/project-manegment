<?php

namespace Database\Factories;

use App\Models\ProjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectTypeFactory extends Factory
{
    protected $model = ProjectType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'type' => $this->faker->randomElement(['project', 'maintenance']),
        ];
    }

    public function project(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'project',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'maintenance',
        ]);
    }
}
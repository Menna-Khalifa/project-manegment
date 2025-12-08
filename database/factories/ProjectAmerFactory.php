<?php

namespace Database\Factories;

use App\Models\ProjectAmer;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectAmerFactory extends Factory
{
    protected $model = ProjectAmer::class;

    public function definition(): array
    {
        return [
            'po_num' => 'PO-' . $this->faker->unique()->numerify('######'),
            'dept' => $this->faker->randomElement(['project', 'facility', 'maintenance', 'other']),
            'region' => $this->faker->randomElement(['western_province', 'central_province', 'eastern_province', 'general']),
            'store_id' => Store::factory(),
            'user_id' => User::factory(),
            'po_file' => $this->faker->optional()->filePath(),
            'priority' => $this->faker->randomElement(['high', 'medium', 'low']),
            'date' => $this->faker->date(),
            'request_status' => $this->faker->randomElement(['new_order', 'cancelled', 'under_working', 'completed', 'on_hold']),
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }

    public function newOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_status' => 'new_order',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'request_status' => 'completed',
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }
}
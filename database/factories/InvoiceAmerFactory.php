<?php

namespace Database\Factories;

use App\Models\InvoiceAmer;
use App\Models\ProjectAmer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceAmerFactory extends Factory
{
    protected $model = InvoiceAmer::class;

    public function definition(): array
    {
        return [
            'project_amer_id' => ProjectAmer::factory(),
            'invoice_number' => 'INV-' . $this->faker->unique()->numerify('######'),
            'amount' => $this->faker->randomFloat(2, 500, 50000),
            'payment_file' => $this->faker->filePath(),
            'status' => $this->faker->randomElement(['paid', 'invoice_issuse', 'pending', 'canceled', 'ready_of_invoicing', 'submitted']),
            'notes' => $this->faker->optional()->paragraph(),
            'created_by' => User::factory(),
            'approved_at' => $this->faker->optional()->dateTime(),
            'approved_by' => $this->faker->optional()->randomElement([null, User::factory()]),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'approved_at' => now(),
            'approved_by' => User::factory(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
        ]);
    }
}
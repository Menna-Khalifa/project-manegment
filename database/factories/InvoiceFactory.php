<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Static counter to ensure unique invoice numbers during seeding
     */
    protected static int $invoiceCounter = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $amount = fake()->numberBetween(0, 100);
        $hasDiscount = fake()->boolean(30);
        $discountType = $hasDiscount ? fake()->randomElement(['fixed', 'percentage']) : null;
        $discountAmount = $hasDiscount ? ($discountType === 'fixed' ? fake()->numberBetween(5, 20) : fake()->numberBetween(5, 30)) : null;

        $totalAmount = $amount;
        if ($hasDiscount) {
            if ($discountType === 'fixed') {
                $totalAmount = max(0, $amount - $discountAmount);
            } else {
                $totalAmount = $amount - ($amount * $discountAmount / 100);
            }
        }

        $issueDate = fake()->dateTimeBetween('-3 months', 'now');
        $dueDate = fake()->dateTimeBetween($issueDate, '+30 days');
        $status = fake()->randomElement(['unpaid', 'paid', 'overdue']);
        $paidAt = $status === 'paid' ? fake()->dateTimeBetween($issueDate, 'now') : null;

        // Generate a unique invoice number using the static counter
        static::$invoiceCounter++;
        $invoiceNumber = 'INV-' . now()->format('Y') . '-' . str_pad(static::$invoiceCounter, 6, '0', STR_PAD_LEFT);

        return [
            'invoice_number' => $invoiceNumber,
            'user_id' => $user->id,
            'amount' => $amount,
            'discount_amount' => $discountAmount,
            'discount_type' => $discountType,
            'total_amount' => $totalAmount,
            'status' => $status,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'paid_at' => $paidAt,
            'payment_method' => $status === 'paid' ? fake()->randomElement(['manual', 'stripe', 'paypal', 'bank_transfer']) : null,
            'notes' => fake()->boolean(40) ? fake()->paragraph(1) : null,
        ];
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
            'paid_at' => fake()->dateTimeBetween($attributes['issue_date'], 'now'),
            'payment_method' => fake()->randomElement(['manual', 'stripe', 'paypal', 'bank_transfer']),
        ]);
    }

    /**
     * Indicate that the invoice is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'unpaid',
            'paid_at' => null,
            'payment_method' => null,
        ]);
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'overdue',
            'due_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
            'paid_at' => null,
            'payment_method' => null,
        ]);
    }
}

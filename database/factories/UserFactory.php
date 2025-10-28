<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Admin;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    public function definition(): array
    {
        // Generate email from name + random number
        $emailName = strtolower($this->faker->name) . rand(1, 999);
        $email = $emailName . '@example.com';

        // Generate realistic Egyptian phone number
        $phonePrefix = ['010', '011', '012', '015'];
        $phone = $phonePrefix[array_rand($phonePrefix)] . rand(10000000, 99999999);

        return [
            'name' => $this->faker->name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Default password
            'phone' => $phone,
            'group_id' => Group::inRandomOrder()->first()?->id ?? 1,
            'type' => $this->faker->randomElement(['admin', 'user']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the admin is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the admin is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the user is admin.
     */
    public function adminType(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is user.
     */
    public function userType(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'user',
        ]);
    }
}

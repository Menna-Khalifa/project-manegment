<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'uuid' => Str::uuid(),
            'name' => $this->faker->company() . ' Store',
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'country' => $this->faker->optional()->country(),
            'city' => $this->faker->optional()->city(),
            'state' => $this->faker->optional()->state(),
            'address' => $this->faker->optional()->address(),
            'zip' => $this->faker->optional()->postcode(),
        ];
    }
}
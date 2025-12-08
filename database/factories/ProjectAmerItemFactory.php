<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\ProjectAmer;
use App\Models\ProjectAmerItem;
use App\Models\ProjectCapacity;
use App\Models\ProjectModel;
use App\Models\ProjectType;
use App\Models\ProjectVolt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectAmerItemFactory extends Factory
{
    protected $model = ProjectAmerItem::class;

    public function definition(): array
    {
        return [
            'project_amer_id' => ProjectAmer::factory(),
            'project_type_id' => ProjectType::factory(),
            'project_model_id' => $this->faker->optional()->randomElement([null, ProjectModel::factory()]),
            'project_capacity_id' => $this->faker->optional()->randomElement([null, ProjectCapacity::factory()]),
            'project_volt_id' => $this->faker->optional()->randomElement([null, ProjectVolt::factory()]),
            'brand_id' => $this->faker->optional()->randomElement([null, Brand::factory()]),
            'qty' => $this->faker->numberBetween(1, 50),
        ];
    }
}
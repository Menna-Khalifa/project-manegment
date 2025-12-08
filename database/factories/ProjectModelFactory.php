<?php

namespace Database\Factories;

use App\Models\ProjectModel;
use App\Models\ProjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectModelFactory extends Factory
{
    protected $model = ProjectModel::class;

    public function definition(): array
    {
        return [
            'project_type_id' => ProjectType::factory(),
            'name' => $this->faker->bothify('Model-####-??'),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
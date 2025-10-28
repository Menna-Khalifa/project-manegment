<?php

namespace Database\Seeders;

use App\Models\ProjectTeam;
use Illuminate\Database\Seeder;

class ProjectTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectTeams = [
            // Project 1 Team
            ['project_id' => 1, 'user_id' => 1],
            ['project_id' => 1, 'user_id' => 2],
            ['project_id' => 1, 'user_id' => 3],

            // Project 2 Team
            ['project_id' => 2, 'user_id' => 2],
            ['project_id' => 2, 'user_id' => 4],
            ['project_id' => 2, 'user_id' => 5],

            // Project 3 Team
            ['project_id' => 3, 'user_id' => 1],
            ['project_id' => 3, 'user_id' => 3],
            ['project_id' => 3, 'user_id' => 6],

            // Project 4 Team
            ['project_id' => 4, 'user_id' => 4],
            ['project_id' => 4, 'user_id' => 5],
            ['project_id' => 4, 'user_id' => 7],
            ['project_id' => 4, 'user_id' => 8],

            // Project 5 Team
            ['project_id' => 5, 'user_id' => 1],
            ['project_id' => 5, 'user_id' => 6],
            ['project_id' => 5, 'user_id' => 7],
        ];

        foreach ($projectTeams as $team) {
            ProjectTeam::create($team);
        }
    }
}

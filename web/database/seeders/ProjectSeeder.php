<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $projects = [];

        for ($i=0; $i < 20; $i++) { 
            $project = [
                'name' => $faker->country(),
                'uuid' => (string)Str::uuid(),
            ];
            $projects[] = $project;
        }

    }
}

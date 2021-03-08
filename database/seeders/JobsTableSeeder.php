<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Task;
use App\Models\Skill;

class JobsTableSeeder extends Seeder
{

    public function run()
    {
        factory(Job::class, 35)->create()->each(function ($job) {

            $Tasks = factory(Task::class, rand(1, 20))->create()->each(function ($Task) {

                $skills = factory(Skill::class, rand(1, 20))->create();

                $Task->skills()->attach($skills);
            });

            $job->tasks()->saveMany($Tasks);
        });
    }
}

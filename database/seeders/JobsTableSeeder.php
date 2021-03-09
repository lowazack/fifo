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
        $jobs = ["Testing", "[MANY] Admin", "[MANY] Marketing & Sales Plan", "Marketing Strategy", "Marketing and Sales Strategy", "[MANY] HIT LIST", "[ANTI] Admin", "[ETH] Pre-launch", "[ANTI] Content Board", "[AMUR] Initial Rebrand", "[JIM] Adhoc", "[JIM] Subscriptions", "[MEAS] Report", "[MEAS] Adhoc", "[ROLL] Homepage Redesign", "[SISO] Adhoc", "[JPS] Adhoc", "To Do"];

        foreach ($jobs as $job){
            Job::create([
                'name' => $job,
                'provider' => 'asana',
            ]);
        }
    }
}

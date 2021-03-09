<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            for ($i = 1; $i <= 20; $i++) {
                Task::create([
                    'user_id' => $user->id,
                    'job_id' => Job::all()->random()->id,
                    'deadline' => (new Carbon())->addWeeks(rand(2,6))->addDays(rand(0, 6)),
                    'priority' => rand(1, 5),
                    'summary' => $this->getRandomSummary()
                ]);
            }
        }
    }

    private function getRandomSummary()
    {
        $summaries = [
            "Cross browser testing on Browserstack",
            "Get Helen some sales",
            "testing in cross browser",
            "Testing cards",
            "Testing task one!",
            "This was a missing headline",
            "Elevator pitch",
            "The Meatless Company",
            "Ananas Anam (Pinatex)"
        ];

        return $summaries[array_rand($summaries, 1)];
    }
}

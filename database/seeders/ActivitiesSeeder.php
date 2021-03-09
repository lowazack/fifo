<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Activity::create([
            'name' => 'Development',
        ]);

        Activity::create([
            'name' => 'Account Management',
        ]);

        Activity::create([
            'name' => 'SEO',
        ]);
    }
}

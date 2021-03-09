<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ActivitiesSeeder::class,
            ClientsTableSeeder::class,
            JobsTableSeeder::class,
            TasksSeeder::class
        ]);
    }
}

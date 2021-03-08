<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{

    public function run()
    {
        \Artisan::call('passport:install');
    }
}

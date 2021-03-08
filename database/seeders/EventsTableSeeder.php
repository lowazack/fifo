<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventsTableSeeder extends Seeder
{

    public function run()
    {
        factory(Event::class, 35)->create();
    }
}

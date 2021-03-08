<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;

use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $holidays = UkBankHolidayFactory::getAll('england-and-wales');

        foreach ($holidays as $holiday) {
            $date = new Carbon($holiday->date);

            $holiday = Holiday::create([
                'start' => $date->copy()->startOfDay(),
                'end' => $date->copy()->endOfDay(),
                'note' => $holiday->title
            ]);

            User::all()->each(function ($item, $key) use ($holiday) {
                $item->holidays()->attach($holiday);
            });
        }
    }
}

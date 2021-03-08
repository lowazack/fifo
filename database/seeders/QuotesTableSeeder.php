<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;
use App\Models\Item;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Quote::class, 35)->create()->each(function ($quote) {

            $Items = factory(Item::class, rand(1, 20))->make();

            $quote->items()->saveMany($Items);
        });
    }
}

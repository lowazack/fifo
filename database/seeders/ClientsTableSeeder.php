<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Contact;

class ClientsTableSeeder extends Seeder
{

    public function run()
    {
        factory(Client::class, 10)->create()->each(function ($client) {

            $contacts = factory(Contact::class, rand(1, 20))->make();

            $client->contacts()->saveMany($contacts);
        });
        ;
    }
}

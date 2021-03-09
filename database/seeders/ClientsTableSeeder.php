<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Contact;

class ClientsTableSeeder extends Seeder
{

    public function run()
    {
        $clients = ["Antibrand", "Jimmy's Iced Coffee", "Many", "Amura", "Measmerize", "Rollover", "Silicon South", "JPS Communications"];

        foreach ($clients as $client){
            Client::create([
                'name' => $client
             ]);
        }
    }
}

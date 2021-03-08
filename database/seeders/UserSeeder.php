<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Ryan',
            'last_name' => 'England',
            'email' => 'ryan@many.co.uk',
            'password' => 'ryanspassword',
            'jira' => 'ryan@many.co.uk',
            'googlecalendar' => 'ryan@many.co.uk',
            'phone' => '07943511899',
            'slack' => 'U8S1P9STX',
            'warns' => 0,
            'availability' => [
                'monday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'tuesday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'wednesday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'thursday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'friday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'saturday' => [],
                'sunday' => [],
            ]
        ]);

        User::create([
            'first_name' => 'Helen',
            'last_name' => 'Khan',
            'email' => 'helen@many.co.uk',
            'password' => 'helenspassword',
            'jira' => 'helen@many.co.uk',
            'googlecalendar' => 'helen@many.co.uk',
            'phone' => '07775511310',
            'slack' => 'U018X8EGATY',
            'warns' => 0,
            'availability' => [
                'monday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'tuesday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'wednesday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'thursday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'friday' => [
                    '0930:1300',
                    '1400:1730'
                ],
                'saturday' => [],
                'sunday' => [],
            ]
        ]);
    }
}

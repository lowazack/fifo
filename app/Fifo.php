<?php

namespace App;

use Illuminate\Support\Collection;

class Fifo
{
    /**
     * Get an inspiring quote.
     *
     * Taylor & Dayle made this commit from Jungfraujoch. (11,333 ft.)
     *
     * May McGinnis always control the board. #LaraconUS2015
     *
     * RIP Charlie - Feb 6, 2018
     *
     * @return string
     */
    public static function TimeTrackingPrompt($user, $minutes)
    {
        return Collection::make([
            $user->first_name . ', Ryan\'s asked me to remind you to track your time',
            'It looks like you haven\'t tracked time for ' . $minutes . ' minutes. With Fifo coming soon, it\'s really important that you track time in real time. Please could you start?',
            'It is astonishing how long it takes to finish something you\'re not working on. Please track your time ' . $user->first_name . '.',
            'We need to start tracking time accurately as a team; it looks like you\'ve not tracked time for ' . $minutes . ' minutes - please could you bring this up to date.',
            'Reminder! You haven\'t tracked time in  ' . $minutes . ' minutes. Track your time please, ' . $user->first_name,
            'Time and tide wait for no man, ' . $user->first_name . '. Please make sure you track your time as you work.',
            'It looks like you\'ve forgotten to start your timer. Please get it started and make sure you stay up to date as you work.'
        ])->random();
    }
}

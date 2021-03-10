<?php

namespace App\Observers;

use App\Events\TeamTimersUpdated;
use App\Events\UserTimersUpdated;
use App\Models\TimeEntry;

class TimerObserver
{
    /**
     * Handle the TimeEntry "created" event.
     *
     * @param TimeEntry $timeEntry
     * @return void
     */
    public function created(TimeEntry $timeEntry)
    {

        event(new UserTimersUpdated());
    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function updated(TimeEntry $timeEntry)
    {

    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function deleted(TimeEntry $timeEntry)
    {
        //
    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function restored(TimeEntry $timeEntry)
    {
        //
    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function forceDeleted(TimeEntry $timeEntry)
    {
        //
    }
}

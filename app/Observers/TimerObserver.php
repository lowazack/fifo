<?php

namespace App\Observers;


use App\Events\Timer as TimerEvent;
use App\Models\TimeEntry;

class TimerObserver
{
    /**
     * @param TimeEntry $timeEntry
     */
    public function created(TimeEntry $timeEntry)
    {
        event(new TimerEvent($timeEntry, 'TimerCreated'));
    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function updated(TimeEntry $timeEntry)
    {
        event(new TimerEvent($timeEntry, 'TimerUpdated'));
    }

    /**
     * @param TimeEntry $timeEntry
     */
    public function deleted(TimeEntry $timeEntry)
    {
        event(new TimerEvent($timeEntry, 'TimerDeleted'));
    }

}

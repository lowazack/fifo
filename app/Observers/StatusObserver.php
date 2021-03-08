<?php

namespace App\Observers;

use App\Models\Status;
use App\Events\Job;

class StatusObserver
{

    public function created(Status $status)
    {
        event(new Job($status->job, 'JobUpdated'));
    }

    public function updated(Status $status)
    {
        event(new Job($status->job, 'JobUpdated'));
    }

    public function deleted(Status $status)
    {
        event(new Job($status->job, 'JobUpdated'));
    }
}

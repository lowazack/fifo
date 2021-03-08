<?php

namespace App\Observers;

use App\Jobs\UpdateHarvest;
use App\Models\Task;
use App\Events\Task as TaskEvent;

class TaskObserver
{

    public function findOrCreateHarvestEntry($task)
    {
        UpdateHarvest::dispatch($task);
    }

    /**
     * @param Task $task
     */
    public function created(Task $task)
    {
        event(new TaskEvent($task, 'TaskCreated'));
        $this->findOrCreateHarvestEntry($task);
    }

    /**
     * @param Task $task
     */
    public function updated(Task $task)
    {
        event(new TaskEvent($task, 'TaskUpdated'));
        $this->findOrCreateHarvestEntry($task);
    }

    /**
     * @param Task $task
     */
    public function deleted(Task $task)
    {
        event(new TaskEvent($task, 'TaskDeleted'));
    }
}

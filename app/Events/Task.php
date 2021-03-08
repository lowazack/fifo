<?php

namespace App\Events;

use App\Models\Task as TaskModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Task implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    private $name;

    /**
     * Task constructor.
     * @param TaskModel $task
     * @param $name
     */
    public function __construct(TaskModel $task, $name)
    {
        $this->name = $name;
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tasks');
    }

    /**
     * @return mixed
     */
    public function broadcastAs()
    {
        return $this->name;
    }

}

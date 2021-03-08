<?php

namespace App\Events;

use App\Models\Job as JobModel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Job implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job;

    private $name;

    /**
     * Job constructor.
     * @param JobModel $job
     * @param $name
     */
    public function __construct(JobModel $job, $name)
    {
        $this->name = $name;
        $this->job = $job;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('jobs');
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return $this->name;
    }
    
}
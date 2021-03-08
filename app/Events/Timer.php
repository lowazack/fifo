<?php

namespace App\Events;

use App\Models\TimeEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Timer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timer;

    private $name;

    /**
     * Timer constructor.
     * @param TimeEntry $timer
     * @param $name
     */
    public function __construct(TimeEntry $timer, $name)
    {
        $this->name = $name;
        $this->timer = $timer;
    }

    /**
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('timers');
    }

    /**
     * @return mixed
     */
    public function broadcastAs()
    {
        return $this->name;
    }
}

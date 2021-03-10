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
use JetBrains\PhpStorm\Pure;

class TimerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $timer;


    /**
     * TimerUpdated constructor.
     * @param $timeEntry
     */
    public function __construct($timeEntry)
    {
        $this->timer = $timeEntry;
    }

    /**
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('timer-'.$this->timer->id),
            new Channel('my-timer-'.$this->timer->user_id)
        ];
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Timer-Updated';
    }
}

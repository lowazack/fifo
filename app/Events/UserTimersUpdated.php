<?php

namespace App\Events;

use App\Models\TimeEntry;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTimersUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel("user-timers");
    }

    public function broadcastAs(): string
    {
        return 'Timers-Updated';
    }

}

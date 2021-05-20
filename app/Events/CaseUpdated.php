<?php

namespace App\Events;

use App\Models\ReferCase;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $case;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ReferCase $case)
    {
        $this->case = $case;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

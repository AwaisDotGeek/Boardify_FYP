<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message details
     *
     * @var \App\Message
     */
    public $message;
    public $chatId;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chatId)
    {
        $this->message = $message;
        $this->chatId = $chatId;
    }

    public function broadcastOn(): Channel
    {
        if ($this->chatId) {
            return new Channel('chat.' . $this->chatId);
        }

        return new Channel('chat');
    }
}

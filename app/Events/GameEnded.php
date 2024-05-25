<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameEnded implements ShouldBroadcast
{
    public $winner;
    public $board;

    public function __construct(User $winner, array $board)
    {
        $this->winner = $winner;
        $this->board = $board;
    }

    public function broadcastOn()
    {
        return new Channel('tictactoe_channel');
    }
}

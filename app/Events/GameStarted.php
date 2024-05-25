<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameStarted implements ShouldBroadcast
{
    public $players = [];
    public string $gameUrl = '';

    public string $gameName = '';

    public function __construct(User $player1, User $player2, string $gameUrl, string $gameName)
    {
        $this->players = [$player1, $player2];
        $this->gameUrl = $gameUrl;
        $this->gameName = $gameName;
    }

    public function broadcastOn()
    {
        return new Channel($this->gameName . '_channel');
    }
}

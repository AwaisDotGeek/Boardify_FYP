<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MoveMade implements ShouldBroadcast
{
    public $gameData;
    public $gameName;

    public function __construct($gameData, $gameName)
    {
        $this->gameData = $gameData;
        $this->gameName = $gameName;
    }

    public function broadcastOn(): Channel
    {
        return new Channel($this->gameName . '_channel.' . $this -> gameData['gameUrl']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicTacToe extends Model
{
    use HasFactory;

    protected $table = 'tictactoe';

    protected  $fillable = [
        'player1_id',
        'player2_id',
        'game_url',
        'player1_symbol',
        'player2_symbol',
        'history',
        'status'
    ];

    public function player1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id', 'id');
    }

    public function player2(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chess extends Model
{
    use HasFactory;

    protected $table = 'chess';

    protected $fillable = [
        'player1_id',
        'player2_id',
        'player1_color',
        'player2_color',
        'player1_captures',
        'player2_captures',
        'game_url',
        'status',
        'history',
        'winner'
    ];

    // Define the relationship with the User model for player1
    public function player1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    // Define the relationship with the User model for player2
    public function player2(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'player2_id');
    }
}

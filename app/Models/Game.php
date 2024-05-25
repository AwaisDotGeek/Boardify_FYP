<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected  $fillable = [
      'player1_id',
      'player2_id',
      'status'
    ];

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id', 'id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id', 'id');
    }
}

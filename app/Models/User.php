<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

/**
 * @method static create(array $user)
 * @method static count()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'email',
        'password',
        'verification_code',
        'puzzles_solved',
    ];

    protected $primaryKey = 'user_id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_id' => 'string',
    ];

    public function setPasswordAttribute($value): void
    {
        $this -> attributes['password'] = Hash::make($value);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function tictactoeGames()
    {
        return $this->hasMany(Game::class, 'player1_id', 'id')
            ->orWhere('player2_id', $this->id);
    }

    /**
     * Get the media record associated with the user.
     */
    public function media()
    {
        return $this->hasOne(Media::class);
    }
}




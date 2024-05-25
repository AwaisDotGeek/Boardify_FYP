<?php

use Illuminate\Support\Facades\Broadcast;

use App\Models\TicTacToe;
use App\Models\Chess;
use App\Models\Chat;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    return $user;
});

Broadcast::channel('tictactoe_lobby', function ($user){
    return $user;
});

Broadcast::channel('tictactoe_channel', function ($user) {
    return $user;
});

Broadcast::channel('tictactoe_play.{gameUrl}', function ($user, $gameUrl) {
    // Get the TicTacToe game based on the game URL
    $game = TicTacToe::where('game_url', $gameUrl)->first();

    // Check if the game exists and is active
    if ($game && $game->status === 'active') {
        // Check if the user is one of the involved players
        if ($user->user_id == $game->player1_id || $user->user_id == $game->player2_id) {
            return $user;
        }
    }

    // If the logic fails, return null (user is not allowed to listen)
    return null;
});

// <------------- Chess ------------->
Broadcast::channel('chess_lobby', function ($user){
    return $user;
});

Broadcast::channel('chess_channel', function ($user){
    return $user;
});

Broadcast::channel('chess_play.{gameUrl}', function ($user, $gameUrl) {
    // Get the TicTacToe game based on the game URL
    $game = Chess::where('game_url', $gameUrl)->first();

    // Check if the game exists and is active
    if ($game && $game->status === 'active') {
        // Check if the user is one of the involved players
        if ($user->user_id == $game->player1_id || $user->user_id == $game->player2_id) {
            return $user;
        }
    }

    // If the logic fails, return null (user is not allowed to listen)
    return null;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Get the chat based on the chat id
    $chat = Chat::where('chat_id', $chatId)->first();

    // Check if the chat exists
    if ($chat ) {
        return $user;
    }

    // If the logic fails, return null (user is not allowed to listen)
    return null;
});



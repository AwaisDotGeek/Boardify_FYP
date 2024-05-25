<?php

namespace App\Http\Controllers;
use App\Events\MoveMade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\TicTacToeUpdate;
use App\Events\GameStarted;

use App\Models\User;
use App\Models\TicTacToe;
use App\Models\MatchmakingQueue;

class TicTacToeController extends Controller
{
    public function startGame(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $opponent = $this -> findOpponent($user);

        if ($opponent) {
            // Create a new game instance and notify both users
            $uniqueId = uniqid();
            $gameUrl = '/tictactoe/game/' . $uniqueId;

            // Broadcast the GameStarted event with the board
            event(new GameStarted($user, $opponent, $gameUrl, 'tictactoe'));

            $this -> addPlayersTo_TicTacToe_Table($user, $opponent, $uniqueId);

            return response()->json([
                'status' => true,
                'message' => 'Game started',
                'gameUrl' => $gameUrl,
            ]);
        } else {
            $this->addToMatchmakingQueue($user);

            return response()->json([
                'status' => false,
                'message' => 'Waiting for another player',
            ]);
        }
    }

    // Helper function that add players to tictactoe table after game is initialized
    protected function addPlayersTo_TicTacToe_Table($player1, $player2, $gameUrl): void
    {
        TicTacToe::create([
            'player1_id' => $player1 -> user_id,
            'player2_id' => $player2 -> user_id,
            'player1_symbol' => 'X',
            'player2_symbol' => 'O',
            'game_url' => $gameUrl,
        ]);
    }

    protected function findOpponent(User $user)
    {
        // Find an opponent in the matchmaking queue
        $opponent = MatchmakingQueue::where('user_id', '!=', $user -> user_id)->first();

        if ($opponent) {
            // If an opponent is found, remove both users from the queue
            MatchmakingQueue::where('user_id', $user -> user_id)->delete();
            MatchmakingQueue::where('user_id', $opponent -> user_id)->delete();

            $opponent = User::where('user_id', $opponent -> user_id)->first();
        }

        return $opponent ? : null;
    }

    protected function addToMatchmakingQueue(User $user): void
    {
        // Check if the user is already in the queue
        $queuedUser = MatchmakingQueue::where('user_id', $user->user_id)->where('game', 'tictactoe')->first();

        if (!$queuedUser) {
            // If not in the queue, add the user to the queue
            MatchmakingQueue::create(['user_id' => $user->user_id, 'game' => 'tictactoe']);
        }
    }

    // who is playing as player 1 and who is playing as player 2
    public function getPlayersSequence(Request $request): \Illuminate\Http\JsonResponse
    {
        $game_url = $request -> game_url;

        $game = TicTacToe::where('game_url', $game_url)->first();
        $player1 = User::where('user_id', $game -> player1_id)->first();
        $player2 = User::where('user_id', $game -> player2_id)->first();


        $player1['symbol'] = $game -> player1_symbol;
        $player2['symbol'] = $game -> player2_symbol;
        return response() -> json([
            'message' => 'Players sequence fetched',
            'player1' => $player1,
            'player2' => $player2,
            'movesHistory' => json_decode($game -> history),
            'isFinished' => $game -> status === 'finished',
            ]);
    }

    // Make a move
    public function makeMove(Request $request): \Illuminate\Http\JsonResponse
    {
        $gameUrl = $request -> gameUrl;
        $game = TicTacToe::where('game_url', $gameUrl) -> first();

        if ($game -> status === 'finished') {
            return response() -> json(['status' => false, 'message' => 'This game was finished!']);
        }

        // Extract the move data from the request
        $selectedIndex = $request->input('index');

        $symbol = $request -> currentPlayer['symbol'];
        $player = new User($request -> currentPlayer);

        $moves = $request -> moves;
        $gameData = [
            'gameUrl' => $gameUrl,
            'player' => $player,
            'symbol' => $symbol,
            'selectedIndex' => $selectedIndex,
            'board' => $request -> cells,
            'moves' => $moves,
        ];

        // Retrieve the current game state (or load it from your storage, e.g., a database)


        // Do this => Check if the game has been won or if it's a draw and update the 'winner' accordingly
        $gameStatus = $this -> isGameOver($request -> cells);
        $gameData['gameStatus'] = $gameStatus;

        if ($gameStatus['isOver'] || $gameStatus['isFull']) {
            $game -> status = 'finished';
            $game -> save();
        }

        // Add the moves to database
        $transformedMoves = array_map(function ($move) {
            return str_replace(' on ', '->', $move);
        }, $moves);

        $this->addMovesToHistory($gameUrl, $transformedMoves);

        // Broadcast the updated game state to all connected clients
        broadcast(new MoveMade($gameData, 'tictactoe'))->toOthers();

        // Respond with a success message (optional)
        return response()->json(['status' => true, 'message' => 'Move made!']);
    }

    // Helper method to add moves to the history column in the tictactoe table
    protected function addMovesToHistory($gameUrl, $moves): void
    {
        // Assuming your tictactoe table has a 'history' column
        $game = TicTacToe::where('game_url', $gameUrl) -> first();
        $game -> history = json_encode($moves);
        $game -> save();
    }

    protected function isGameOver($board): array
    {
        // Check horizontal
        for ($i = 0; $i < 9; $i += 3) {
            if ($this->checkLine($board, $i, $i + 1, $i + 2)) {
                return ['isOver' => true, 'isFull' => false];
            }
        }

        // Check vertical
        for ($i = 0; $i < 3; $i++) {
            if ($this->checkLine($board, $i, $i + 3, $i + 6)) {
                return ['isOver' => true, 'isFull' => false];
            }
        }

        // Check diagonally
        if ($this->checkLine($board, 0, 4, 8) || $this->checkLine($board, 2, 4, 6)) {
            return ['isOver' => true, 'isFull' => false];
        }

        // check board full
        for ($i = 0; $i < 9; $i++) {
            if ($board[$i]) {
                continue;
            } else {
                return ['isOver' => false, 'isFull' => false];
            }
        }

        return ['isOver' => false, 'isFull' => true];

    }

    private function checkLine($board, $pos1, $pos2, $pos3): bool
    {
        return $board[$pos1] !== null && $board[$pos1] === $board[$pos2] && $board[$pos1] === $board[$pos3];
    }

    // Restart the game
    public function restartGame(Request $request): void
    {
        $game = TicTacToe::where('game_url', $request -> gameUrl)->first();
        $game -> status = 'active';
        $game -> history = json_encode([]);
        $game -> save();
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\GameStarted;
use App\Events\MoveMade;
use App\Models\MatchmakingQueue;
use App\Models\User;
use App\Models\Chess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChessController extends Controller
{
    // Start a new game
    public function startGame(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $opponent = $this -> findOpponent($user);

        if ($opponent) {
            // Create a new game instance and notify both users
            $uniqueId = uniqid();
            $gameUrl = '/chess/game/' . $uniqueId;

            // Broadcast the GameStarted event with the board
            event(new GameStarted($user, $opponent, $gameUrl, 'chess'));

            $this -> addPlayersTo_Chess_Table($user, $opponent, $uniqueId);

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

    // Get the opponent
    protected function findOpponent(User $user)
    {
        // Find an opponent in the matchmaking queue
        $opponent = MatchmakingQueue::where('user_id', '!=', $user->user_id)
            ->where('game', 'chess')
            ->first();

        if ($opponent) {
            // If an opponent is found, remove both users from the queue
            MatchmakingQueue::where('user_id', $user -> user_id)->delete();
            MatchmakingQueue::where('user_id', $opponent -> user_id)->delete();

            $opponent = User::where('user_id', $opponent -> user_id)->first();
        }

        return $opponent ? : null;
    }

    // Add player o matchmaking queue
    protected function addToMatchmakingQueue(User $user): void
    {
        // Check if the user is already in the queue
        $queuedUser = MatchmakingQueue::where('user_id', $user->user_id)->first();

        if (!$queuedUser) {
            // If not in the queue, add the user to the queue
            MatchmakingQueue::create(['user_id' => $user->user_id, 'game' => 'chess']);
        }
    }

    // Add players to chess table (this is done after the game is started)
    protected function addPlayersTo_Chess_Table($player1, $player2, $gameUrl): void
    {
        Chess::create([
            'player1_id' => $player1 -> user_id,
            'player2_id' => $player2 -> user_id,
            'player1_color' => 'white',
            'player2_color' => 'black',
            'game_url' => $gameUrl,
        ]);
    }

    // who is playing as player 1 and who is playing as player 2
    public function getPlayersSequence(Request $request): \Illuminate\Http\JsonResponse
    {
        $game_url = $request -> game_url;

        $game = Chess::where('game_url', $game_url)->first();
        $player1 = User::where('user_id', $game -> player1_id)->first();
        $player2 = User::where('user_id', $game -> player2_id)->first();


        $player1['color'] = $game -> player1_color;
        $player2['color'] = $game -> player2_color;
        return response() -> json([
            'message' => 'Players sequence fetched',
            'player1' => $player1,
            'player2' => $player2,
//            'movesHistory' => json_decode($game -> history),
            'isFinished' => $game -> status === 'finished',
        ]);
    }

    // Make Move
    public function makeMove(Request $request): \Illuminate\Http\JsonResponse
    {
        $gameUrl = $request -> gameUrl;
        $game = Chess::where('game_url', $gameUrl) -> first();

        if ($game -> status === 'finished') {
            return response() -> json(['status' => false, 'message' => 'This game was finished!']);
        }

        // Extract the move data from the request
        $boardState = $request->input('boardState');

        $player = $request -> currentPlayer;

        $gameData = [
            'gameUrl' => $gameUrl,
            'player' => $player,
            'boardState' => $boardState,
            'movedFrom' => $request -> input('movedFrom'),
            'movedTo' => $request -> input('movedTo'),
        ];

        // Retrieve the current game state (or load it from your storage, e.g., a database)


        // Do this => Check if the game has been won or if it's a draw and update the 'winner' accordingly
//        $gameStatus = $this -> isGameOver($request -> cells);
//        $gameData['gameStatus'] = $gameStatus;
//
//        if ($gameStatus['isOver'] || $gameStatus['isFull']) {
//            $game -> status = 'finished';
//            $game -> save();
//        }

        // Add the moves to database
//        $transformedMoves = array_map(function ($move) {
//            return str_replace(' on ', '->', $move);
//        }, $moves);

//        $this->addMovesToHistory($gameUrl, $transformedMoves);

        // Broadcast the updated game state to all connected clients
        broadcast(new MoveMade($gameData, 'chess'))->toOthers();

        // Respond with a success message (optional)
        return response()->json(['status' => true, 'message' => 'Move made!']);
    }


    // Save Game state
    public function saveGameStateInDB(Request $request) {
        $gameUrl = $request -> gameUrl;

        $game = Chess::where('game_url', $gameUrl)->first();

        $boardState = $request -> boardState;
        $moves = $request -> moves;
        $player1Captures = $request -> player1Captures;
        $player2Captures = $request -> player2Captures;

        $game['history'] = $moves;
        $game['board_state'] = $boardState;
        $game['player1_captures'] = $player1Captures;
        $game['player2_captures'] = $player2Captures;

        $game -> save();
    }

    // Get Game state from DB
    public function getGameStateFromDB(Request $request) {
        $gameUrl = $request -> gameUrl;

        $game = Chess::where('game_url', $gameUrl)->first();

        if ($game -> history) {
            return response()->json(['status' => true, 'game' => $game]);
        }

        return response()->json(['status' => false]);
    }

    public function finishTheGame(Request $request) {
        $gameUrl = $request -> gameUrl;

        $game = Chess::where('game_url', $gameUrl)->first();

        if ($game) {
            if ($game->status === 'active') {
                $game -> status = 'finished';
                $game -> winner = $request -> winner;
                return response() -> json(['message' => 'Game Finished Successfully!']);
            }
            return response() -> json(['message' => 'Game has already been finished!']);
        }

        return response() -> json(['message', 'Game does not exist!']);
    }
}

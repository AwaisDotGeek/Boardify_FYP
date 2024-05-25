<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\TicTacToeController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ChessController;
use Illuminate\Http\Request;

use App\Models\Chess;
use App\Models\TicTacToe;
use App\Models\Media;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChessPuzzle;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
//   return Inertia::render('Games/Ludo/Game');
    return Inertia::render('dashboard/Home');
})->middleware('auth');

Route::get('/welcome', function () {
    return Inertia::render('basic/Welcome');
});

Route::get('/signup', function() {
    return Inertia::render('users/Signup');
});

Route::get('/login', function () {
   return Inertia::render('users/Login');
})->name('login');

Route::get('/reset-password', function () {
    return Inertia::render('users/ResetPassword');
});

Route::post('/get_verification_code', [VerificationController::class, 'getVerificationCode']);

Route::post('/signup', [UserController::class, 'createUser']);

Route::get('/verify-email', [VerificationController::class, 'showVerifyCodePage']);

Route::post('/verify-email/', [VerificationController::class, 'verifyVerificationCode']);

Route::post('/login', [UserController::class, 'loginUser']);

Route::post('/check-email-existence', [VerificationController::class, 'checkEmailExistence']);

Route::post('/verify-email-code', [VerificationController::class, 'verifyEmailCode']);

Route::post('/reset-password', [VerificationController::class, 'resetPassword']);


// Discussions
Route::get('/chat', [ChatsController::class, 'index']);
Route::get('/messages', [ChatsController::class, 'fetchMessages']);
Route::post('messages', [ChatsController::class, 'sendMessage']);

// Games
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', function () {
        return Inertia::render('users/Profile');
    });

    Route::get('/user-details', function (Request $request) {
        if ($request->has('user_id')) {
            $user = User::where('user_id', $request->user_id)->first();
            // Proceed with handling the user data
        } else {
            // Handle the case where user_id parameter is not provided
            return response()->json(['error' => 'user_id parameter is missing'], 400);
        }

        $userDetails = [
           'username' => $user['username'],
           'email' => $user['email'],
           'country' => $user['country'],
           'photoUrl' => Media::where('user_id', $user -> user_id)->first(),
        ];

       if ($userDetails['photoUrl']) {
           $userDetails['photoUrl'] = 'storage/' . $userDetails['photoUrl']->photo_url;
       }

       return response() -> json(['user' => $userDetails]);
    });

    Route::get('/fetch-profile-photo', function () {
        $user = Auth::user();
        $photoUrl = Media::where('user_id', $user -> user_id)->first()->photo_url;
        if ($photoUrl) {
            $photoUrl = 'storage/' . $photoUrl;
        }
        return response() -> json(['photoUrl' => $photoUrl]);
    });

    Route::post('/save-user-details', function (Request $request) {
        $user = Auth::user();

        // Validate and update the form data
        $validatedData = $request->validate([
            'country' => 'string',
            'profile_photo' => 'nullable|file',
        ]);

        // Update user's country
        $user['country'] = $validatedData['country'];

        // Handle profile photo upload if provided
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');

            // Store the profile photo in a specific directory
            // Store the profile photo using Storage facade
            // $profilePhotoPath = Storage::put('profile-photos', $profilePhoto);
            $profilePhotoPath = Storage::disk('public')->put('profile-photos', $profilePhoto);

            // Move the profile photo to the public directory
//            $publicProfilePhotoPath = str_replace('storage/app/public', 'public', $profilePhotoPath);
//            Storage::move($profilePhotoPath, $publicProfilePhotoPath);

            $media = Media::where('user_id', $user->user_id)->first();
            if (!$media) {
                $media = new Media();
                $media->user_id = $user->user_id;
            }
            $media->photo_url = $profilePhotoPath;
            $media->save();
        }

        // Save the changes to the user model
        $user->save();
    });

    Route::post('/initialize-chat', function (Request $request){
        $chatId = $request -> gameUrl;

        if (!$chatId) {
            return response() -> json(['message' => 'Chat id was not sent!', 'chat_id' => $request -> gameUrl]);
        }

        $chat = Chat::where('chat_id', $chatId)->first();
        if($chat) {
            return response() -> json(['message' => 'Chat already initialized!']);
        }
        $chat = new Chat();
        $chat['chat_id'] = $chatId;
        $chat['messages'] = json_encode([]);
        $chat -> save();

        return response() -> json(['message' => 'Chat initialized!']);
    });

    Route::get('/get-messages-using-id', function (Request $request) {
        $chatId = $request -> chatId;

        if (!$chatId) {
            return response() -> json(['message' => "Chat id was not sent!"]);
        }

        $chat = Chat::where('chat_id', $chatId)->first();
        if (!$chat) {
            return response() -> json(['message' => 'No chat was found with that id']);
        }

        $messages = json_decode($chat -> messages);

        return response() -> json(['messages' => $messages] );
    });

    Route::post('/send-message-using-id', function (Request $request) {
        $chatId = $request -> chatId;

        if (!$chatId) {
            return response() -> json(['message' => 'Chat id was not sent!']);
        }

        $chat = Chat::where('chat_id', $chatId)->first();

        if (!$chat) {
            return response() -> json(['message' => 'Chat with provided id was not found!']);
        }

        $messages = json_decode($chat->messages, true);
        $newMessage = [
            'user' => $request -> sender,
            'message' => $request -> message
        ];
        $messages[] = $newMessage;

        $chat -> messages = json_encode($messages);
        $chat -> save();

        broadcast(new \App\Events\MessageSent($newMessage, $chatId));

        return response() -> json(['message' => 'Message sent successfully!']);
    });

    // <============ TicTacToe ============>
    Route::get('/tictactoe/play', function (){
        return Inertia::render('Games/TicTacToe/TicTacToeLobby');
    })->middleware('auth');
    Route::post('/tictactoe/start-game', [TicTacToeController::class, 'startGame']);
    Route::post('/tictactoe/make-move', [TicTacToeController::class, 'makeMove']);

    Route::get('/tictactoe/game/{game_url}', function ($game_url) {
        $game = TicTacToe::where('game_url', $game_url)->first();

        // Check if the user is either player1 or player2
        $user = Auth::user();

        if(!$user) {
            return Inertia::render('basic/Welcome');
        }

        if ($game && ($user->user_id == $game->player1_id || $user->user_id == $game->player2_id)) {
            return Inertia::render('Games/TicTacToe/Game');
        } else {
            return Inertia::render('dashboard/Home');
        }
    });

    Route::post('/get_players_sequence', [TicTacToeController::class, 'getPlayersSequence']);
    Route::post('/tictactoe/make_move', [TicTacToeController::class, 'makeMove']);
    Route::post('/tictactoe/restart_game', [TicTacToeController::class, 'restartGame']);

    // <============ Chess ============>
    // <----- Initial Chess Page ----->
    Route::get('/chess/play', function () {
        return Inertia::render('Games/Chess/Home');
    });
    Route::get('/chess/puzzles', function () {
        return Inertia::render('Games/Chess/Puzzles');
    });
    Route::get('/get-random-chess-puzzle', function () {
        $user = Auth::user();

        $puzzlesSolved = $user -> puzzles_solved;

        // Generate a random number between 1 and 10000
//        $randomId = rand(1, 100000);

        // Retrieve the chess puzzle with the ID
        $puzzle = ChessPuzzle::find($puzzlesSolved + 1);

        return response()->json(['puzzle' => $puzzle]);
    });
    Route::post('/update-puzzles-solved', function () {
        $user = Auth::user();
        $user -> puzzles_solved += 1;
        $user -> save();
    });
    Route::get('/chess/lobby', function (){
        return Inertia::render('Games/Chess/ChessLobby');
    });
    Route::post('/chess/start-game', [ChessController::class, 'startGame']);
    Route::post('/chess/get_players_sequence', [ChessController::class, 'getPlayersSequence']);
    Route::post('/chess/make-move', [ChessController::class, 'makeMove']);

    Route::post('/chess/game/save-state', [ChessController::class, 'saveGameStateInDB']);
    Route::post('/chess/game/get-state', [ChessController::class, 'getGameStateFromDB']);
    Route::get('/chess/game/{game_url}', function ($game_url) {

        $game = Chess::where('game_url', $game_url)->first();

        // Check if the user is either player1 or player2
        $user = Auth::user();

        if(!$user) {
            return Inertia::render('basic/Welcome');
        }

        if ($game && ($user->user_id == $game->player1_id || $user->user_id == $game->player2_id)) {
            return Inertia::render('Games/Chess/Game');
        } else {
            return Inertia::render('dashboard/Home');
        }
    });

    Route::post('/finish-chess-game', [ChessController::class, 'finishTheGame']);

    Route::post('/chess/reset', function(Request $request) {
        $gameUrl = $request -> gameUrl;
        $game = Chess::where('game_url', $gameUrl)->first();
        $game -> history = null;
        $game -> player1_captures = null;
        $game -> player2_captures = null;
        $game -> board_state = null;
        $game -> save();
    });
});

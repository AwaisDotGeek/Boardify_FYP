<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Inertia\Inertia ;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Response
    {
        return Inertia::render('discussions/Chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request): void
    {
        $user = Auth::user();

        $user -> messages() -> create([
            'message' => $request -> message,
        ]);
    }
}

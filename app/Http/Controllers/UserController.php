<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Inertia\Inertia;

class UserController extends Controller
{
    public function loginUser(Request $request) {
        $credentials = $request -> validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255']
        ]);

        $user = User::where('email', $request -> input('email'))->first();
        if ($user) {
            if ($user -> email_verified) {
                if (Auth::attempt($credentials, true)) {
                    Auth::user();

                    return redirect('/');
                }
                return Inertia::render('users/Login', [
                    'errors' => ['password' => 'Invalid Credentials.'],
                ]);
            } else {
                session(['user_id' => $user -> user_id]);
                return redirect('/verify-email');
            }
        } else {
            return redirect('/signup');
        }
    }

    // <---------------------------------------------------------->

    public function createUser(Request $request):RedirectResponse
    {
        $user = $request->validate([
            'username' => ['required', 'min:2', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',],
        ]);

        // create user_id
        $usersCount = User::count() + 1;
        $userId = sprintf('%08s', base_convert($usersCount, 10, 36));

        // add user_id and verification_code to attributes
        $user['user_id'] = $userId;
        $user['verification_code'] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Create user
        User::create($user);

        // Mail User
        Mail::to($user['email']) -> send(new SendMail($user));

        // Flash the user_id to the session, so it is available to the next request
        session(['user_id' => $userId]);

        // Redirect
        return redirect('/verify-email');
    }
}

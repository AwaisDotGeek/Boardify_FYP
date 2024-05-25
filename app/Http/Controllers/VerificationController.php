<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class VerificationController extends Controller
{
    // Handles Get
    function showVerifyCodePage(): Response
    {
        $userId = session('user_id');

        return Inertia::render('users/VerifyEmail', [
            'userId' => $userId,
        ]);
    }

    // If user requests a new code:
    function getVerificationCode(): void
    {
        $userId = session('user_id');

        $user = User::find($userId);
        $newCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user -> verification_code = $newCode;
        $user -> save();

        Mail::to($user -> email) -> send(new SendMail($user));
    }

    // Handles Post
    function verifyVerificationCode(Request $request): Response
    {
        $request->validate([
            'verification_code' => ['required', 'numeric', 'regex:/^\d{4}$/'],
        ]);

        $userId = $request -> input('userId');
        $user = User::find($userId);

        if ($user -> verification_code === $request -> input('verification_code')) {

            $user -> email_verified = true;
            $user -> save();

            Auth::login($user);

            return Inertia::render('basic/Welcome');
        } else {
            return Inertia::render('users/VerifyEmail', [
                'userId' => $userId,
                'errors' => ['verification_code' => "The entered code is not correct."],
            ]);
        }
    }

    function resetPassword(Request $request): Response
    {
        // Step 1: Check if the email exists in the database
        if ($request -> input('step') == 1) {
            // Handle email validation logic
            $request->validate([
                'email' => ['required', 'max:50', 'email'],
            ]);

            $email = $request->input('email');

            $user = User::where('email', $email)->first();

            if (!$user) {
                return Inertia::render('users/ResetPassword', [
                    'errors' => ['email' => 'The provided email is not registered!'],
                ]);
            }

            $newCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $user -> verification_code = $newCode;
            $user -> save();

//            Mail::to($user -> email) -> send(new SendMail($user));

            return Inertia::render('users/ResetPassword', [
                'success' => true
            ]);
        }

        // Step 2: Handle email verification
        else if ($request -> input('step') == 2) {
            // Handle email verification logic
            $request -> validate([
                'verification_code' => ['required', 'numeric', 'regex:/^\d{4}$/'],
            ]);

            $user = User::where('email', $request -> input('email'))->first();

            if ($request -> input('verification_code') == $user -> verification_code)
            {
                return Inertia::render('users/ResetPassword', [
                    'success' => true,
                ]);
            }
            else
            {
                return Inertia::render('users/ResetPassword', [
                    'errors' => ['verification_code' => 'Verification code is not correct!'],
                ]);
            }
        }

        // Step 3: Handle password reset
        else if ($request -> input('step') == 3) {
            // Handle password reset logic
            $request -> validate([
               'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',],
            ]);

            $user = User::where('email', $request -> input('email')) -> first();
            $user -> password = $request -> input('password');
            $user -> save();

            Auth::login($user);

            return Inertia::render('basic/Welcome');
        }

        // Return appropriate Inertia response or redirect as needed
        return Inertia::render('basic/Welcome');
    }
}

<?php

namespace App\Http\Controllers;

// Models
use App\Models\PasswordReset;
use App\Models\User;

// Mails
use App\Mail\PasswordResetTokenGenerated;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller {
    
    public function createResetPasswordLink(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if($user) {
            $now = date("Y-m-d H:i:s");
            $token = Hash::make("{$user->email}{$user->passoword}{$now}");

            PasswordReset::create([
                'user_id' => $user->id,
                'email'   => $user->email,
                'token'   => $token
            ]);

            Mail::to($user->email)->queue(new PasswordResetTokenGenerated($token));

            return response()->json([
                'success' => true,
                'message' => 'Token gerado' 
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Usuário não encontrado' 
        ], 404);
    }

    public function resetPassword() {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

}

<?php

namespace App\Http\Controllers;

// Models
use App\Models\PasswordReset;
use App\Models\User;

// Mails
use App\Mail\PasswordResetTokenGenerated;

// Requests
use App\Http\Requests\Password\ForgotPasswordRequest;
use App\Http\Requests\Password\ResetPasswordRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller {
    
    public function forgotPassoword(ForgotPasswordRequest $request) {

        $old_reset_password_token = PasswordReset::where('email', $request->email)->first();

        if($old_reset_password_token) {
            $old_reset_password_token->delete();
        }

        $token = Hash::make($request->email . date("Y-m-d H:i:s"));

        PasswordReset::create([
            'email' => $request->email,
            'token' => $token
        ]);

        Mail::to($request->email)->queue(new PasswordResetTokenGenerated($token));

        return response()->json([
            'success' => true,
            'message' => 'Token gerado' 
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $request) {

        $reset_password = PasswordReset::where('email', $request->email)
                                        ->where('token', $request->token)
                                        ->firstOrFail();

        if($reset_password) {
            $user = User::where('email', $request->email)->first();
            $user->forceFill(['password' => bcrypt($request->password)])->save();

            $reset_password->delete();

            return response()->json([
                'success' => true,
                'message' => 'Senha resetada' 
            ], 200);
        }

        return response(204);
    }

}

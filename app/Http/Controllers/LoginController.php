<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserSignUp;
use JWTAuth;

class LoginController extends Controller
{
    
    public function userSignUp(UserSignUp $request) {

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password)
        ]);

        $custom_claims = [
            'user' => collect($user)->only('first_name', 'last_name', 'email')->toArray(),
        ];
        
        $token = JWTAuth::claims($custom_claims)->attempt($request->all('email', 'password'));

        return response()->json([
            'token_type' => 'bearer',
            'token'      => $token,
            'message'    => 'Usuário cadastrado com sucesso',
            'success'    => true
        ], 200);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Login\UserSignUpRequest;
use App\Http\Requests\Login\UserLoginRequest;
use JWTAuth;

class LoginController extends Controller
{
    
    public function userSignUp(UserSignUpRequest $request) {

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

    public function userLogin(UserLoginRequest $request) {
        $user = User::whereEmail($request->email)
                    ->firstOrFail();

        $custom_claims = [
            'user' => collect($user)->only('first_name', 'last_name', 'email')->toArray(),
        ];
        
        $token = JWTAuth::claims($custom_claims)->attempt($request->all('email', 'password'));

        if(!$token) {
            return response()->json([
                'message' => 'Email ou senha incorretos',
                'success' => false
            ], 401);
        }

        return response()->json([
            'token_type' => 'bearer',
            'token'      => $token,
            'message'    => 'Usuário logado com sucesso',
            'success'    => true
        ], 200);
    }

    public function userLogout(Request $request) {
        $has_token = $request->bearerToken();

        if($has_token) {
            JWTAuth::invalidate(JWTAuth::getToken());
    
            return response()->json([], 204);
        }

        return response()->json([
            'message' => 'É necessário informar um token',
            'success' => false
        ], 412);
    }

}

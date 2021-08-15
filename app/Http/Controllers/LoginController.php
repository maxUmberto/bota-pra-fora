<?php

namespace App\Http\Controllers;

// requests
use App\Http\Requests\Login\UserSignUpRequest;
use App\Http\Requests\Login\UserLoginRequest;

// models
use App\Models\User;

use Illuminate\Http\Request;
use JWTAuth;
class LoginController extends Controller
{
    
    /**
     * Creates a new user at the database and returns its JWT token
     * 
     * @param Illuminate\Foundation\Http\FormRequest $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Check if the user credentials is valid and then generates its JWT token, log it in
     * 
     * @param Illuminate\Foundation\Http\FormRequest
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Logout the current user
     * 
     * @param Illuminate\Foundation\Http\FormRequest
     * @return Illuminate\Http\Request
     */
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

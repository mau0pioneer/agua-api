<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\APIHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) APIHelper::responseFailed([
            'message' => 'Credenciales incorrectas.',
            'errors' => $validator->errors()
        ], 400);

        if (!$token = JWTAuth::attempt($credentials)) APIHelper::responseFailed([
            'message' => 'Credenciales incorrectas.',
            'errors' => [
                'password' => ['La contraseña es incorrecta.']
            ]
        ], 400);
        
        // Si todo sale bien, retornamos el token y el usuario
        return response()->json([
            'jwt' => $token,
            'user' => JWTAuth::user()
        ]);
    }

    public function getUserByToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $user = JWTAuth::toUser($token);
            // Si todo sale bien, retornamos el usuario y el token
            return response()->json([
                'jwt' => $token,
                'user' => $user
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Error al decodificar el token'], 500);
        }
    }
}

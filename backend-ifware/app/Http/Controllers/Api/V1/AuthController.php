<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //Valida os dados do usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            //Recupera o usuario autenticado
            $user = Auth::user();

            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Credenciais inválidas'
            ], 404);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Pega o usuário autenticado através do token na requisição
            $user = $request->user();

            // Revoga (invalida) o token de acesso atual que foi usado para autenticar esta requisição
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout realizado com sucesso. O token atual foi invalidado.'
            ], 200);
        } catch (Exception $e) {
            // Em caso de um erro inesperado, retornamos um status 500 (Internal Server Error)
            // pois o erro não foi do cliente (400), mas sim do servidor.
            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro ao tentar realizar o logout.',
                // Em ambiente de desenvolvimento, você pode querer adicionar:
                // 'error' => $e->getMessage()
            ], 500);
        }
    }
}

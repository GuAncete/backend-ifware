<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // <-- Importante para senhas
use Illuminate\Support\Facades\Log; // <-- Para registrar erros
use Spatie\QueryBuilder\QueryBuilder;
use \Exception; // <-- Usar a exceção global

class UserController extends Controller
{
    /**
     * Retorna uma lista paginada de usuários.
     *
     * Permite filtrar por 'name', 'email' e 'role'.
     * Ex: GET /api/v1/users?filter[name]=John&page=1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(['name', 'email', 'role'])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return response()->json($users, 200);
    }

    /**
     * Cria um novo usuário no banco de dados.
     *
     * A validação dos dados de entrada é feita através do StoreUserRequest.
     *
     * @param  \App\Http\Requests\V1\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        // Os dados já foram validados pelo StoreUserRequest
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // <-- SEMPRE use hash na senha!
                'role' => $validatedData['role']
            ]);

            DB::commit();
            return response()->json($user, 201); // 201 Created é o status correto para criação

        } catch (Exception $e) {
            DB::rollBack();
            // Registra o erro para que possa ser depurado depois
            Log::error('Falha ao criar usuário: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao cadastrar usuário.'], 500); // 500 para erro interno
        }
    }

    /**
     * Exibe um usuário específico.
     *
     * O usuário é injetado automaticamente pelo Route Model Binding.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user, 200);
    }

    /**
     * Atualiza os dados de um usuário específico.
     *
     * A validação dos dados de entrada é feita através do UpdateUserRequest.
     *
     * @param  \App\Http\Requests\V1\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // Se uma nova senha foi enviada, faz o hash. Senão, mantém a antiga.
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($validatedData);

            DB::commit();
            return response()->json($user, 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Falha ao atualizar usuário: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao atualizar usuário.'], 500);
        }
    }

    /**
     * Remove um usuário do banco de dados.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            // 204 No Content é uma ótima resposta para exclusão bem-sucedida, pois não precisa de corpo.
            return response()->json(["message" => "Usuário deletado com sucesso."], 204);

        } catch (Exception $e) {
            Log::error('Falha ao deletar usuário: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao deletar usuário.'], 500);
        }
    }
}
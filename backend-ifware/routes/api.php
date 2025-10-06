<?php

// Importando todos os controllers do namespace Api/V1

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\V1\TaskCollaboratorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas da sua API. Todas estas rotas
| são automaticamente prefixadas com '/api' pelo Laravel.
|
*/

// Agrupa todas as rotas da versão 1 sob o prefixo 'v1'
// Ex: /api/v1/users
Route::prefix('v1')->group(function () {

    /**
     * Simplificando as rotas de CRUD com apiResource.
     * Cada linha abaixo cria automaticamente as 5 rotas para cada recurso:
     *
     * GET      /recurso        -> index()
     * GET      /recurso/{id}   -> show()
     * POST     /recurso        -> store()
     * PUT/PATCH /recurso/{id}  -> update()
     * DELETE   /recurso/{id}   -> destroy()
     */

    // --- Rotas Públicas (Autenticação) ---
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/users', [UserController::class, 'index']); 
        Route::get('/user/{user}', [UserController::class, 'show']); 
        Route::post('/user', [UserController::class, 'store']); 
        Route::put('/user/{user}', [UserController::class, 'update']); 
        Route::delete('/user/{user}', [UserController::class, 'destroy']);
        
        Route::get('/clients', [ClientController::class, 'index']);
        Route::get('/clients/{client}', [ClientController::class, 'show']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::put('/clients/{client}', [ClientController::class, 'update']);
        Route::delete('/clients/{client}', [ClientController::class, 'destroy']);

        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

        Route::get('/tasks', [TaskController::class, 'index']);
        Route::get('/tasks/{task}', [TaskController::class, 'show']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

         // Rotas aninhadas para gerenciar colaboradores da tarefa
        Route::post('/tasks/{task}/collaborators', [TaskCollaboratorController::class, 'store'])
             ->name('v1.tasks.collaborators.store');
        
        Route::delete('/tasks/{task}/collaborators/{user}', [TaskCollaboratorController::class, 'destroy'])
             ->name('v1.tasks.collaborators.destroy');
        

    });
});

<?php

// Importando todos os controllers do namespace Api/V1
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
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

    Route::apiResource('users', UserController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);

});
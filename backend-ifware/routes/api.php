<?php

use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Retorna a lista de projetos, com filtros opcionais
    Route::get('/projects', [ProjectController::class, 'index']);

    // Retorna os detalhes de um projeto específico, incluindo suas tarefas
    Route::get('/projects/{project}', [ProjectController::class, 'show']);

    // Retorna os detalhes de uma tarefa específica
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    
    // Atualiza o status de uma tarefa
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
});
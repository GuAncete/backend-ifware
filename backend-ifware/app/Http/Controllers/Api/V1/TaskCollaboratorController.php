<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User; // Importe o User se for usar no destroy
use Illuminate\Http\Request;

class TaskCollaboratorController extends Controller
{
    /**
     * Associa um colaborador (usuário) a uma tarefa.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Verifica se a relação já não existe para evitar duplicatas
        if ($task->collaborators()->where('user_id', $request->user_id)->exists()) {
            return response()->json(['message' => 'Colaborador já está na tarefa.'], 409); // 409 Conflict
        }

        $task->collaborators()->attach($request->user_id);

        return response()->json(['message' => 'Colaborador adicionado com sucesso!'], 201);
    }

    
    /**
     * Desassocia um colaborador de uma tarefa.
     */
    public function destroy(Task $task, User $user)
    {
        $task->collaborators()->detach($user->id);

        return response()->json(null, 204); // 204 No Content
    }
}
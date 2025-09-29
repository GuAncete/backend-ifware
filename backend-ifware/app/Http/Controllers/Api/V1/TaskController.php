<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Mostra uma tarefa específica com seus usuários.
     */
    public function show(Task $task)
    {
        $task->load(['assignedUsers', 'project.client']);
        return response()->json($task);
    }

    /**
     * Atualiza o status de uma tarefa.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:A Fazer,Em Andamento,Concluído'],
        ]);

        $task->update(['status' => $validated['status']]);

        // Regra de negócio: Se uma tarefa é concluída,
        // podemos adicionar lógicas futuras aqui, como notificar alguém.
        if ($validated['status'] === 'Concluído') {
            // Lógica de notificação futura...
        }

        return response()->json($task->fresh('assignedUsers'));
    }
}
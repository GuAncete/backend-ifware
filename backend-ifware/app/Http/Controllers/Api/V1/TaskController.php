<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters(['title', 'status', 'priority'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate();
        DB::beginTransaction();
        try {
            $task = Task::create([
                'tituloTask'      => $validatedData['tituloTask'],
                'descricaoTask'   => $validatedData['descricaoTask'],
                'statusTask'      => $validatedData['statusTask'],
                'ordemTask'       => $validatedData['ordemTask'],
                'projetoId'       => $validatedData['projetoId'],
            ]);
            DB::commit();
            return response()->json($task, 201); // 201 Created é o status correto para criação
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar tarefa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar tarefa'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate();
        DB::beginTransaction();
        try {
            $task->update($validatedData);
            DB::commit();
            return response()->json($task, 200); // 200 OK é o status correto para atualização
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar tarefa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar tarefa'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try{
            $task->delete();
            return response()->json(['message' => 'Tarefa deletada com sucesso.'], 200);
        } catch (Exception $e) {
            Log::error('Falha ao deletar tarefa: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao deletar tarefa.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Lista todos os projetos com seus relacionamentos e filtros.
     */
    public function index(Request $request)
    {
        $projects = Project::with(['client', 'projectType'])
            ->when($request->query('search'), function ($query, $search) {
                $query->where('nomeProjeto', 'like', "%{$search}%")
                      ->orWhereHas('client', function ($q) use ($search) {
                          $q->where('nomeCliente', 'like', "%{$search}%");
                      });
            })
            ->when($request->query('tipoProjetoId'), function ($query, $typeId) {
                $query->where('tipoProjetoId', $typeId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('client.nomeCliente'); // Agrupa os projetos pelo nome do cliente

        return response()->json($projects);
    }

    /**
     * Mostra um projeto específico com suas tarefas e usuários atribuídos.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'projectType', 'tasks.assignedUsers']);

        return response()->json($project);
    }
}
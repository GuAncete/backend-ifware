<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProjectRequest;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters(['nomeProjeto', 'descricaoProjeto', 'statusProjeto'])
            ->orderBy('nomeProjeto')
            ->paginate(10)
            ->appends(request()->query());
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
    public function store(ProjectRequest $request)
    {
        $validatedData = $request->validate();
        DB::beginTransaction();
        try{
            $project = Project::create([
                'nomeProjeto'       => $validatedData['nomeProjeto'],
                'descricaoProjeto'  => $validatedData['descricaoProjeto'],
                'statusProjeto'     => $validatedData['statusProjeto'],
                'clienteId'         => $validatedData['clienteId'],
            ]);
            DB::commit();
            return response()->json($project, 201); // 201 Created é o status correto para criação
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar projeto: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar projeto'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return response()->json($project);
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
    public function update(ProjectRequest $request, Project $project)
    {
        $validatedData = $request->validate();

        DB::beginTransaction();
        try {
            $project->update($validatedData);
            DB::commit();
            return response()->json($project);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar o projeto.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try{
            $project->delete();
            return response()->json(['message' => 'Projeto deletado com sucesso.'], 200);
        } catch (Exception $e) {
            Log::error('Falha ao deletar projeto: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao deletar projeto.'], 500);
        }
    }
}

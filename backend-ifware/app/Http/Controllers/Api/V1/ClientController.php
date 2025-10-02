<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ClientRequest;
use App\Models\Client;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // <-- Para registrar erros
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = QueryBuilder::for(Client::class)
            ->allowedFilters(['nomeCliente', 'emailCliente', 'telefoneCliente', 'statusCliente'])
            ->orderBy('nomeCliente')
            ->paginate(10)
            ->appends(request()->query());

        return response()->json($clients, 200);
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
    public function store(ClientRequest $request)
    {
        $validatedData = $request->validate();

        DB::beginTransaction();
        try{
            $client = Client::create([
                'nomeCliente'     => $validatedData['nomeCliente'],
                'emailCliente'    => $validatedData['emailCliente'],
                'telefoneCliente' => $validatedData['telefoneCliente'],
                'statusCliente'   => $validatedData['statusCliente'],
                'descricaoCliente'   => $validatedData['descricaoCliente'],
            ]);

            DB::commit();
            return response()->json($client, 201); // 201 Created é o status correto para criação
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar cliente: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar cliente'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json($client, 200);
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
    public function update(ClientRequest $request, Client $client): JsonResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $client->update($validatedData);

            DB::commit();
            return response()->json($client, 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Falha ao atualizar cliente: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao atualizar cliente.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        try{
            $client->delete();
            return response()->json(['message' => 'Cliente deletado com sucesso.'], 200);
        } catch (Exception $e) {
            Log::error('Falha ao deletar cliente: ' . $e->getMessage());
            return response()->json(['message' => 'Falha ao deletar cliente.'], 500);
        }
    }
}

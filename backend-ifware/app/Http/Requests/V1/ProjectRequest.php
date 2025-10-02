<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Importante para regras mais complexas

class ProjectRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * * CORRIGIDO: Deve retornar `true` para permitir a validação,
     * ou conter a lógica de autorização real.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtém as regras de validação que se aplicam à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // REATORADO: Estrutura mais limpa e sem repetição de código.

        if ($this->isMethod('POST')) {
            // Regras para a criação (store)
            return [
                'nomeProjeto' => 'required|string|max:255',
                'descricaoProjeto' => 'required|string',
                'statusProjeto' => 'required|integer',
                // Usando Rule::exists para melhor legibilidade
                'clienteId' => ['required', Rule::exists('clients', 'id')],
            ];
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Regras para a atualização (update)
            // 'sometimes' garante que a validação só ocorra se o campo for enviado.
            return [
                'nomeProjeto' => 'sometimes|string|max:255',
                'descricaoProjeto' => 'sometimes|string',
                'statusProjeto' => 'sometimes|integer',
                'clienteId' => ['sometimes', Rule::exists('clients', 'id')],
            ];
        }

        // Se não for POST, PUT ou PATCH, não aplicamos regras.
        return [];
    }


    /**
     * Customiza mensagens de erro (opcional).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nomeProjeto.required' => 'O campo nome do projeto é obrigatório.',
            'descricaoProjeto.required' => 'O campo descrição do projeto é obrigatório.',
            'statusProjeto.required' => 'O campo status do projeto é obrigatório.',
            'clienteId.required' => 'O campo cliente é obrigatório.',
            'clienteId.exists' => 'O cliente selecionado não existe.',
        ];
    }
}

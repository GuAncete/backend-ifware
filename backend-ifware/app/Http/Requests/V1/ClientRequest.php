<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Se a requisição for para CRIAR um usuário (POST)
        if ($this->isMethod('POST')) {
            return [
                'nomeCliente'     => ['required', 'string', 'max:255'],
                'emailCliente'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'telefoneCliente' => ['required', 'string', 'max:255'],
                'statusCliente'   => ['required', 'integer', Rule::in([0, 1])],
            ];
        }

        // Se a requisição for para ATUALIZAR um usuário (PUT ou PATCH)
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Pega o usuário que está sendo atualizado a partir da rota (ex: /users/{user})
            $user = $this->route('user');

            return [
                'nomeCliente'     => ['sometimes', 'string', 'max:255'],
                'emailCliente'    => ['sometimes', 'string', 'email', 'max:255'],
                'telefoneCliente' => ['sometimes', 'string', 'max:255'],
                'statusCliente'   => ['sometimes', 'integer', Rule::in([0, 1])],
            ];
        }

        // Retorno padrão caso o método não seja nenhum dos acima
        return [];
    }
    /**
     * Customizar mensagens de erro (opcional).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nomeCliente.required' => 'O campo nome é obrigatório.',
            'emailCliente.required' => 'O campo e-mail é obrigatório.',
            'emailCliente.unique' => 'Este e-mail já está em uso.',
            'telefoneCliente.required' => 'O campo telefone é obrigatório.',
            'statusCliente.in' => 'O status selecionado é inválido.',
        ];
    }
}

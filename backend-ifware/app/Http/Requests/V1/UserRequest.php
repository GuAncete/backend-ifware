<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Para simplificar, vamos permitir todas as requisições.
        // Em um cenário real, você poderia adicionar lógicas diferentes aqui:
        // if ($this->isMethod('POST')) { return $this->user()->isAdmin(); }
        // if ($this->isMethod('PUT')) { ... }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Se a requisição for para CRIAR um usuário (POST)
        if ($this->isMethod('POST')) {
            return [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed'],
                'role'     => ['required', 'string', Rule::in(['admin', 'user'])],
            ];
        }

        // Se a requisição for para ATUALIZAR um usuário (PUT ou PATCH)
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // Pega o usuário que está sendo atualizado a partir da rota (ex: /users/{user})
            $user = $this->route('user');

            return [
                'name'     => ['sometimes', 'string', 'max:255'],
                'email'    => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['sometimes', 'string', 'confirmed'],
                'role'     => ['sometimes', 'string', Rule::in(['admin', 'user'])],
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
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'role.in' => 'O perfil selecionado é inválido.',
        ];
    }
}
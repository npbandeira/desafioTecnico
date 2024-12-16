<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'cpf' => 'required|unique:usuarios,cpf|regex:/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', // Validação de CPF
            'password' => 'sometimes|required|string|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string válida.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Já existe um usuário registrado com este e-mail.',

            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.unique' => 'Já existe um usuário registrado com este CPF.',
            'cpf.regex' => 'O CPF informado é inválido. Utilize o formato: 123.456.789-00.',

            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'A senha deve ser uma string válida.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiFormRequest
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
            'name' => 'sometimes|string|min:3|max:50',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($this->user()->id), // Ignorar el email actual
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return
        [
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.email' => 'Debe ser un correo válido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}

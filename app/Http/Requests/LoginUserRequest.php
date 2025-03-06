<?php

namespace App\Http\Requests;

class LoginUserRequest extends ApiFormRequest
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
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8',

        ];
    }

    public function messages()
    {
        return
            [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Debe ser un correo válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ];
    }
}

<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class RegisterUserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * SE pone en true para que pueda entrar al metodo donde lo usamos
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     * Reglas que debe cumplir
     */
    public function rules(): array
    {
        return
            [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|string|email|max:100|unique:users,email',
                'password' =>
                'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#._-])[A-Za-z\d@$!%*?&#._-]+$/',
            ];
    }

    //Mensajes para cada error
    public function messages()
    {
        return
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.min' => 'El nombre debe tener al menos 3 caracteres.',

                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Debe ser un correo válido.',
                'email.unique' => 'Este correo ya está registrado.',

                'password.required' => 'La contraseña es obligatoria',
                'password.string' => 'La contraseña debe ser una cadena de texto',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'password.regex' => 'La contraseña debe contener: al menos 1 minúscula, 1 mayúscula, 1 número, 1 caracter especial (@$!%*?&#._-) y solo estos caracteres permitidos'
            ];
    }
}

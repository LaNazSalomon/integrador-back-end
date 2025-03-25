<?php

namespace App\Http\Requests;


class PasswordRequest extends ApiFormRequest
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
        return
            [

                'token' =>
                'required|string|size:10|regex:/^[a-f0-9]{10}$/i',
                'password' =>
                'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#._-])[A-Za-z\d@$!%*?&#._-]+$/',
                'email' => 'required|email|exists:users,email',

            ];
    }

    public function messages()
    {
        return
            [
                'password.required' => 'La contraseña es obligatoria',
                'password.string' => 'La contraseña debe ser una cadena de texto',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres',
                'password.confirmed' => 'Las contraseñas no coinciden',
                'password.regex' => 'La contraseña debe contener: al menos 1 minúscula, 1 mayúscula, 1 número, 1 caracter especial (@$!%*?&#._-) y solo estos caracteres permitidos',

                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'Debe ingresar un correo electrónico válido',
                'email.exists' => 'No existe ningún usuario registrado con este correo',

                'token.required' => 'El código de verificación es obligatorio',
                'token.size' => 'El código debe tener exactamente 10 caracteres',
                'token.regex' => 'El código solo puede contener letras y números',
            ];
    }
}

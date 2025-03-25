<?php

namespace App\Http\Requests;


class KeyPasswordRequest extends ApiFormRequest
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
            'token' =>
            'required|string|size:10|regex:/^[a-f0-9]{10}$/i',
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.exists' => 'No existe ningún usuario registrado con este correo',

            'token.required' => 'El código de verificación es obligatorio',
            'token.size' => 'El código debe tener exactamente 10 caracteres',
            'token.regex' => 'El código solo puede contener letras y números',
        ];
    }
}

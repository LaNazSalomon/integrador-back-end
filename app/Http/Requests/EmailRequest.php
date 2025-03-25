<?php

namespace App\Http\Requests;


class EmailRequest extends ApiFormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo es requerido',
            'email.email' => 'El correo no es valido',
            'email.max' => 'El correo no puede tener mas de 255 caracteres',
            'email.exists' => 'No hay ningún usuario con este correo.'
        ];
    }
}

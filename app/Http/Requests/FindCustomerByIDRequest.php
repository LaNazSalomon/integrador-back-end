<?php

namespace App\Http\Requests;


class FindCustomerByIDRequest extends ApiFormRequest
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
            'email' => 'required|email|max:100',
            'hotel' => 'required|integer|exists:hotels,id'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico es inválido',
            'email.max' => 'El correo no debe exceder los 100 caracteres',
            'hotel.required' => 'El ID del hotel es requerido',
            'hotel.integer' => 'El ID del hotel debe ser un número entero',
            'hotel.exists' => 'El hotel especificado no existe'
        ];
    }
}

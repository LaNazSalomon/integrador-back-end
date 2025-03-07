<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends ApiFormRequest
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
            'hotel_id'   => 'required|integer|exists:hotels,id',
            'name'       => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->where(function ($query) {
                    return $query->where('hotel_id', $this->hotel_id);
                })->ignore($this->route('customer')) // Asume que la ruta tiene parámetro 'customer'
            ],
        ];
    }

    public function messages()
{
    return [
        'hotel_id.required'   => 'El ID del hotel es obligatorio.',
        'hotel_id.integer'    => 'El ID del hotel debe ser un número entero.',
        'hotel_id.exists'     => 'El hotel no existe en el sistema.',

        'name.required'      => 'El nombre es obligatorio.',
        'name.string'        => 'El nombre debe ser un texto.',
        'name.max'           => 'El nombre no debe superar los 255 caracteres.',

        'last_name.required' => 'El apellido es obligatorio.',
        'last_name.string'   => 'El apellido debe ser un texto.',
        'last_name.max'      => 'El apellido no debe superar los 255 caracteres.',

        'email.required'     => 'El correo electrónico es obligatorio.',
        'email.string'       => 'El correo electrónico debe ser un texto.',
        'email.email'        => 'El correo electrónico debe ser válido.',
        'email.max'          => 'El correo electrónico no debe superar los 255 caracteres.',
        'email.unique'       => 'Este correo ya está registrado en el hotel.',
    ];
}
}

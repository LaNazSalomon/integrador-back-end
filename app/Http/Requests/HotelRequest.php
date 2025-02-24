<?php

namespace App\Http\Requests;


class HotelRequest extends ApiFormRequest
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|min:3|max:255',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario no existe en la base de datos.',
            'name.required' => 'El nombre del hotel es obligatorio.',
            'name.min' => 'El nombre del hotel debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre del hotel no puede superar los 255 caracteres.',
        ];
    }
}

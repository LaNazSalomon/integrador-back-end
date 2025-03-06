<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

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
            'name'    => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('hotels')
                    ->where(function ($query) {
                        return $query->where('user_id', $this->user_id);
                    }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists'   => 'El usuario no existe en la base de datos.',
            'name.required'    => 'El nombre del hotel es obligatorio.',
            'name.string'      => 'Debe ser tipo string',
            'name.min'         => 'El nombre del hotel debe tener al menos 3 caracteres.',
            'name.max'         => 'El nombre del hotel no puede superar los 255 caracteres.',
            'name.unique'      => 'Ya existe un hotel con ese nombre para este usuario.',
        ];
    }
}

<?php

namespace App\Http\Requests;


class RoomRequest extends ApiFormRequest
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
            'hotel_id' => 'required|integer|exists:hotels,id',
            'number' => 'required|integer|min:1|unique:rooms,number,NULL,id,hotel_id,' . $this->hotel_id,
            'type' => 'required|string|in:single,double,suite,premium,deluxe,executive,family,king,queen,studio,villa,penthouse',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|in:available,occupied,maintenance',
            'description' => 'required|string|min:5'
        ];
    }

    public function messages()
    {
        return [
            'hotel_id.required' => 'El campo hotel es obligatorio.',
            'hotel_id.exists' => 'El hotel seleccionado no existe.',

            'number.required' => 'El número de habitación es obligatorio.',
            'number.integer' => 'El número de habitación debe ser un número entero.',
            'number.min' => 'El número de habitación debe ser al menos 1.',
            'number.unique' => 'Ya existe una habitación con ese número en este hotel.',

            'type.required' => 'El tipo de habitación es obligatorio.',
            'type.string' => 'El tipo de habitación debe ser una cadena de texto.',
            'type.in' => 'El tipo de habitación debe ser uno de los siguientes: single, double, suite, premium, deluxe, executive, family, king, queen, studio, villa, penthouse.',

            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',

            'status.required' => 'El estado de la habitación es obligatorio.',
            'status.string' => 'El estado debe ser una cadena de texto.',
            'status.in' => 'El estado debe ser uno de los siguientes: available, occupied, maintenance.',

            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.min' => 'La descripción debe ser de un mínimo de 5 caracteres.',

        ];
    }
}

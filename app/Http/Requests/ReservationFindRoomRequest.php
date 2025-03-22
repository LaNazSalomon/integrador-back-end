<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationFindRoomRequest extends FormRequest
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
            'hotel_id',
            'check_in' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:' . now()->addDays(1)->toDateString(), // Mínimo 3 días después de hoy
            ],
            'check_out' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after:check_in', // Mínimo 1 día después de check_in
            ],
            'type' => [
                'required',
                'string',
                Rule::in([
                    'individual',
                    'doble',
                    'suite',
                    'premium',
                    'deluxe',
                    'ejecutiva',
                    'familiar',
                    'king',
                    'queen',
                    'estudio',
                    'villa',
                    'penthouse'
                ]),
            ],
        ];
    }

    public function messages()
    {
        return [
            'check_in.required' => 'La fecha de entrada es obligatoria.',
            'check_in.date' => 'El formato de la fecha de entrada no es válido.',
            'check_in.after_or_equal' => 'La fecha de entrada debe ser al menos 3 días después de hoy.',

            'check_out.required' => 'La fecha de salida es obligatoria.',
            'check_out.date' => 'El formato de la fecha de check-out no es válido.',
            'check_out.after' => 'La fecha de salida debe ser al menos 1 día después de la de entrada.',

            'type.required' => 'El tipo de habitación es obligatorio.',
            'type.string' => 'El tipo de habitación debe ser una cadena de texto.',
            'type.in' => 'El tipo de habitación seleccionado no es válido.',
        ];
    }
}

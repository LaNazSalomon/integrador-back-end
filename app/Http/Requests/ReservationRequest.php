<?php

namespace App\Http\Requests;


class ReservationRequest extends ApiFormRequest
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
            'customer_id' => 'required|integer|exists:customers,id',
            'rooms' => 'required|array|min:1',
            'rooms.*' => 'integer|exists:rooms,id',
            'reservation_date' => 'required|date',
            'check_in' => 'required|date|after:reservation_date',
            'check_out' => 'required|date|after:check_in',
            'busy_days' => 'array|min:1|unique:reservation_details,busy_days',
            'busy_days.*' => 'date',
            'status' => 'required|string|in:pending,confirmed,canceled,finalized',
            'payment_method' => 'required|string|in:credit_card,debit_card,paypal',
            'people_count' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'hotel_id.required' => 'El campo de hotel es obligatorio.',
            'hotel_id.exists' => 'El hotel no existe en nuestra base de datos.',
            'customer_id.required' => 'El campo de cliente es obligatorio.',
            'customer_id.exists' => 'El cliente no existe en nuestra base de datos.',
            'rooms.required' => 'El campo de habitaciones es obligatorio.',
            'rooms.array' => 'El campo de habitaciones debe ser un arreglo.',
            'rooms.*.exists' => 'Algunas habitaciones no existen en la base de datos.',
            'reservation_date.required' => 'La fecha de reserva es obligatoria.',
            'check_in.required' => 'La fecha de check-in es obligatoria.',
            'check_in.after' => 'La fecha de check-in debe ser posterior a la fecha de reserva.',
            'check_out.required' => 'La fecha de check-out es obligatoria.',
            'check_out.after' => 'La fecha de check-out debe ser posterior a la fecha de check-in.',
            'busy_days.array' => 'Los días ocupados deben ser un arreglo.',
            'busy_days.*.date' => 'Cada día ocupado debe ser una fecha válida.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser uno de los siguientes: pending, confirmed, canceled.',
            'payment_method.required' => 'El método de pago es obligatorio.',
            'payment_method.in' => 'El método de pago debe ser uno de los siguientes: credit_card, debit_card, paypal.',
            'people_count.required' => 'El número de personas es obligatorio.',
            'people_count.min' => 'El número de personas debe ser al menos 1.',
        ];
    }
}

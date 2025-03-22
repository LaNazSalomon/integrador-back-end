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
            'room_id' => 'required|min:1|exists:rooms,id',
            'check_in' => 'required|date|after:reservation_date',
            'check_out' => 'required|date|after:check_in',
            'status' => 'required|string|in:pendiente,confirmada,cancelada,finalizada',
            'payment_method' => 'required|string|in:Tarjeta de Crédito,Tarjeta de Débito,PayPal',
            'people_count' => 'required|integer|min:1',
            'total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/' // Campo total con hasta dos decimales

        ];
    }

    public function messages()
    {
        return [
            'hotel_id.required' => 'El campo de hotel es obligatorio.',
            'hotel_id.exists' => 'El hotel no existe en nuestra base de datos.',

            'customer_id.required' => 'El campo de cliente es obligatorio.',
            'customer_id.exists' => 'El cliente no existe en nuestra base de datos.',

            'room_id.required' => 'El campo de habitaciones es obligatorio.',
            'room_id.exists' => 'La habitacion no existe en nuestra base de datos.',

            'check_in.required' => 'La fecha de entrada es obligatoria.',
            'check_in.after' => 'La fecha de entrada debe ser posterior a la fecha de reserva.',
            'check_out.required' => 'La fecha de salida es obligatoria.',
            'check_out.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',

            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser uno de los siguientes: pendiente, confirmada, finalizada.',

            'payment_method.required' => 'El método de pago es obligatorio.',
            'payment_method.in' => 'El método de pago debe ser uno de los siguientes: Tarjeta de Crédito, Tarjeta de Débito, PayPal.',
            'people_count.required' => 'El número de personas es obligatorio.',
            'people_count.min' => 'El número de personas debe ser al menos 1.',

            'total.required' => 'El total es obligatorio.', // Mensaje de error para total
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total no puede ser un valor negativo.',
            'total.regex' => 'El total debe tener máximo dos decimales.',
        ];
    }
}

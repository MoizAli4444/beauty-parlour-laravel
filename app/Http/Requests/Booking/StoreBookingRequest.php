<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'appointment_time' => 'required|date',
            'offer_id' => 'nullable|exists:offers,id',
            'note' => 'nullable|string|max:1000',
            'status' => 'required|in:0,1',
            'payment_status' => 'required|in:0,1',
            'payment_method' => 'required|in:cash,card,wallet,online',

            'services' => 'nullable|array|min:1',
            'services.*.service_variant_id' => 'nullable|exists:service_variants,id',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.staff_id' => 'nullable|exists:staff,id',

            'addons' => 'nullable|array',
            'addons.*.addon_id' => 'nullable|exists:addons,id',
            'addons.*.price' => 'nullable|numeric|min:0',
            'addons.*.staff_id' => 'nullable|exists:staff,id',
        ];
    }
}

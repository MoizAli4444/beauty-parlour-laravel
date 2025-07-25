<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'leaving_date' => 'nullable|date|after_or_equal:joining_date',
            'cnic' => 'nullable|string|max:25',
            'emergency_contact' => 'nullable|string|max:20',
            'image'                 => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'shift_id' => 'nullable|exists:shifts,id',
            'working_days'          => 'nullable|array',
            'working_days.*'        => 'in:mon,tue,wed,thu,fri,sat,sun',
            'salary'                => 'nullable|numeric|min:0',
            'payment_schedule' => 'nullable|string|in:monthly,weekly,daily',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'bank_account_number' => 'nullable|string|max:30',
            'is_head' => 'required|boolean',

            // Staff fields
            'staff_role_id'         => 'nullable|exists:staff_roles,id',
            'is_verified'           => 'boolean',
            'status'                => 'required|in:active,inactive',
        ];
    }
}

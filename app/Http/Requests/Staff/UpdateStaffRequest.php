<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
        $userId = $this->route('staff')->user_id ?? null;

        return [
            // User fields
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email,' . $userId,
            'password'              => 'nullable|string|min:8|confirmed',

            // Staff fields
            'staff_role_id'         => 'nullable|exists:staff_roles,id',
            'phone'                 => 'nullable|string|max:20',
            'address'               => 'nullable|string|max:255',
            'date_of_birth'         => 'nullable|date',
            'joining_date'          => 'nullable|date',
            'leaving_date'          => 'nullable|date|after_or_equal:joining_date',
            'is_head'               => 'boolean',
            'cnic'                  => 'nullable|string|max:25',
            'emergency_contact'     => 'nullable|string|max:20',
            'image'                 => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'shift_id'              => 'nullable|exists:shifts,id',
            'working_days'          => 'nullable|array',
            'working_days.*'        => 'in:mon,tue,wed,thu,fri,sat,sun',
            'salary'                => 'nullable|numeric|min:0',
            'payment_schedule'      => 'required|string|in:monthly,weekly',
            'payment_method_id'     => 'nullable|exists:payment_methods,id',
            'bank_account_number'   => 'nullable|string|max:50',
            'is_verified'           => 'boolean',
            'status'                => 'required|in:active,inactive',
        ];
    }
}

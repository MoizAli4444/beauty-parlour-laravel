<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'expense_type'   => 'sometimes|required|string|max:255',
            'amount'         => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|in:cash,cheque,online_payment',
            'date'           => 'sometimes|required|date',
            'receipt_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:6000', 
            'notes'          => 'nullable|string|max:500',
        ];
    }
}

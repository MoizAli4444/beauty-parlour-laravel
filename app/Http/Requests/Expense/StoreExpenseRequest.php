<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
            'expense_type'   => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,cheque,online_payment',
            'date'           => 'required|date',
            'receipt_file'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
            'notes'          => 'nullable|string|max:500',
        ];
    }
}

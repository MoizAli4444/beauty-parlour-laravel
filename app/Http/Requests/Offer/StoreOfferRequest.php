<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
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
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['percentage', 'flat'])],
            'value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($this->type === 'percentage', ['max:100']),
            ],
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'max_total_uses' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:0',
            'offer_code' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:6048',
            'status' => 'required|in:active,inactive',
            'lifecycle' => 'nullable|in:active,expired,upcoming,disabled',
        ];
    }
}

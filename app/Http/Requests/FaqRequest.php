<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
        // Check if updating (FAQ exists) or creating
        $faqId = $this->route('faq') ?? null;

        return [
            'question' => 'required|string|max:255|unique:faqs,question,' . $faqId,
            'answer'   => 'required|string',
            'status'   => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => 'The question field is required.',
            'question.unique'   => 'This question already exists.',
            'answer.required'   => 'Please provide an answer.',
            'status.required'   => 'Please select a status.',
            'status.in'         => 'Status must be either active or inactive.',
        ];
    }
}

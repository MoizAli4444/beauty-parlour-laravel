<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
            'status' => 'required|in:active,inactive',
            // 'slug' => [
            //     'nullable',
            //     'string',
            //     'max:255',
            //     Rule::unique('services', 'slug')->ignore($this->route('service')->id),
            // ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:11000',
        ];
    }
}

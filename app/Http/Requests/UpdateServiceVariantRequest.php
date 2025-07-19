<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceVariantRequest extends FormRequest
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
            'service_id'   => ['nullable', 'exists:services,id'],
            'name'         => ['required', 'string', 'max:255'],
            'image'        => ['nullable', 'image', 'max:11000'],
            'description'  => ['nullable', 'string'],
            'price'        => ['required', 'numeric', 'min:0'],
            'duration'     => ['nullable', 'string', 'max:255'],
            'status'       => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Please select a service.',
            'service_id.exists'   => 'The selected service does not exist.',
            'name.required'       => 'Variant name is required.',
            'image.image'         => 'The uploaded file must be an image.',
            'image.max'           => 'The image must not be greater than 5MB.',
            'price.required'      => 'Price is required.',
            'price.numeric'       => 'Price must be a valid number.',
            'status.required'     => 'Status is required.',
            'status.in'           => 'Status must be either active or inactive.',
        ];
    }
}

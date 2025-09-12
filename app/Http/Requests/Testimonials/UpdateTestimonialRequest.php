<?php

namespace App\Http\Requests\Testimonials;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'testimonial' => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'status'      => ['required', 'in:pending,active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'The name is required.',
            'testimonial.required' => 'The testimonial text is required.',
            'image.image'          => 'The file must be an image.',
            'image.mimes'          => 'Allowed image formats: jpg, jpeg, png, webp.',
            'image.max'            => 'The image may not be greater than 2MB.',
            'status.in'            => 'Invalid status value.',
        ];
    }
}

<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
            'service_id' => 'nullable|exists:service_variants,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4|max:102400', // 100MB
            'featured'    => 'nullable|boolean',
            'alt_text'    => 'nullable|string|max:255',
            'status'      => 'required|in:active,inactive',
        ];
    }
}

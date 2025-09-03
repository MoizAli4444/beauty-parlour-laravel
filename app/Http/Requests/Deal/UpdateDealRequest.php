<?php

namespace App\Http\Requests\Deal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
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
        $dealId = $this->route('deal'); // picks ID from route model binding

        return [
            'name'          => 'required|string|max:255|unique:deals,name,' . $dealId,
            'slug'          => 'nullable|string|max:255|unique:deals,slug,' . $dealId,
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'services_total' => 'nullable|numeric|min:0',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'status'        => 'required|in:active,inactive',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'service_variant_ids' => 'required|array|min:1',
            'service_variant_ids.*' => 'exists:service_variants,id',
        ];
    }
}

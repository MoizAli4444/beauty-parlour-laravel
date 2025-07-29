<?php

namespace App\Http\Requests\Addon;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddonRequest extends FormRequest
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
        $addonId = $this->route('addon'); // Assuming route model binding or id param
// dd($addonId);
        return [
            // 'name' => 'required|string|max:255|unique:addons,name,' . $addonId,
            'name' => 'required|string|max:255|unique:addons,name,' . $addonId . ',id',

            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:11000',
            'status' => 'required|string',
            'duration' => 'nullable|integer|min:0',
            'gender' => 'required|in:0,1,2',

        ];
    }
}

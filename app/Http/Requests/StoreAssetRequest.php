<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_code' => 'nullable|string|unique:assets,asset_code',
            'name' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'notes' => 'nullable|string',
        ];
    }
}

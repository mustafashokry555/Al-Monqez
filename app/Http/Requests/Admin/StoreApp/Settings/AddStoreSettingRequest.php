<?php

namespace App\Http\Requests\Admin\StoreApp\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AddStoreSettingRequest extends FormRequest
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
            'management_ratio' => 'required|integer|min:0|max:100',
            'vat' => 'required|integer|min:0|max:100',
            'delivery_charge' => 'required|numeric|min:0|max:1000000'
        ];
    }
}

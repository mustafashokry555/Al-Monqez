<?php

namespace App\Http\Requests\Admin\ServicesApp\Regions;

use Illuminate\Foundation\Http\FormRequest;

class ValidateRegionRequest extends FormRequest
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
        $this->merge([
            'coordinates' => json_decode($this->input('coordinates'), true)
        ]);

        return [
            'coordinates' => 'required|array|min:3',
            'coordinates.*' => 'required|array|min:2|max:2',
            'coordinates.*.*' => 'required|numeric|min:-180|max:180'
        ];
    }
}

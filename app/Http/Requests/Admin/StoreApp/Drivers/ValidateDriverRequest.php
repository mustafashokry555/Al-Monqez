<?php

namespace App\Http\Requests\Admin\StoreApp\Drivers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateDriverRequest extends FormRequest
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
            'driver_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '5');
                })
            ]
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\StoreApp\Classifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateClassificationRequest extends FormRequest
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
        $rules = [
            'classification_id' => 'required|exists:store_classifications,id'
        ];

        if (auth()->user()->role_id == 6) {
            $rules['classification_id'] = [
                'required',
                Rule::exists('store_classifications', 'id')->where(function ($query) {
                    $query->where('store_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}

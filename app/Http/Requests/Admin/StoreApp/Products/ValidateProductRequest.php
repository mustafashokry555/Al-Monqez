<?php

namespace App\Http\Requests\Admin\StoreApp\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateProductRequest extends FormRequest
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
            'product_id' => 'required|exists:store_products,id',
        ];

        if (auth()->user()->role_id == 6) {
            $rules['product_id'] = [
                'required',
                Rule::exists('store_products', 'id')->where(function ($query) {
                    $query->where('store_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}

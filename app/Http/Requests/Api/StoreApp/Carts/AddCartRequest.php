<?php

namespace App\Http\Requests\Api\StoreApp\Carts;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Rules\StoreApp\MaxQuantityRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCartRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

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
            'product_id' => [
                'required',
                Rule::exists('store_products', 'id')->where(function ($query) {
                    $query->where('displayed', '1');
                }),
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                new MaxQuantityRule($this->product_id)
            ],
        ];
    }
}

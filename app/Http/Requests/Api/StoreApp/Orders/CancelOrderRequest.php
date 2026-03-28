<?php

namespace App\Http\Requests\Api\StoreApp\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CancelOrderRequest extends FormRequest
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
            'order_id' => [
                'required',
                Rule::exists('store_orders', 'id')->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                        ->where('status', '<=', 2);
                })
            ]
        ];
    }
}

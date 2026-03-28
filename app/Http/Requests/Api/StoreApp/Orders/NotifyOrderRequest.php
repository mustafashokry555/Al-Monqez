<?php

namespace App\Http\Requests\Api\StoreApp\Orders;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotifyOrderRequest extends FormRequest
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
            'order_id' => [
                'required',
                Rule::exists('store_orders', 'id')->where(function ($query) {
                    $query->where('store_orders.status', 3)
                        ->where('store_orders.driver_id', auth()->id());
                })
            ]
        ];
    }
}

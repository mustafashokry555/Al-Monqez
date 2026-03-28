<?php

namespace App\Http\Requests\Admin\StoreApp\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteOrderRequest extends FormRequest
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
        $whereConditions = [];

        if (auth()->user()->role_id == 6) {
            $whereConditions[] = ['store_orders.store_id', auth()->id()];
        }
        return [
            'order_id' => [
                'required',
                Rule::exists('store_orders', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                })
            ]
        ];
    }
}

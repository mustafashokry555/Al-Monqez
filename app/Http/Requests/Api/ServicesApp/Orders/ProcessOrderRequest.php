<?php

namespace App\Http\Requests\Api\ServicesApp\Orders;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessOrderRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;

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
        $orderUnderProcess = Order::where([['id', $this->order_id], ['status', 2]])->exists();

        return [
            'order_id' => [
                'required',
                Rule::exists('orders', 'id')->where(function ($query) {
                    $query->where('worker_id', auth()->id())
                        ->where('status', '>=', '1')
                        ->where('status', '<=', '2');
                })
            ],
            'before_images' => [
                Rule::requiredIf($orderUnderProcess),
                'nullable',
                'array',
                'min:1',
                'max:10'
            ],
            'before_images.*' => 'required|mimes:png,jpg,jpeg,webp',
            'after_images' => [
                Rule::requiredIf($orderUnderProcess),
                'nullable',
                'array',
                'min:1',
                'max:10'
            ],
            'after_images.*' => 'required|mimes:png,jpg,jpeg,webp',
        ];
    }
}

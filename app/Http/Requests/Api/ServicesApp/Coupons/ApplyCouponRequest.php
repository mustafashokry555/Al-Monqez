<?php

namespace App\Http\Requests\Api\ServicesApp\Coupons;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\OrderRequest;
use App\Rules\ServicesApp\UsedCoupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyCouponRequest extends FormRequest
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
        $clientOrderOffer = OrderRequest::join('orders', 'order_requests.order_id', '=', 'orders.id')
            ->where([['order_requests.id', $this->offer_id], ['orders.status', '0'], ['orders.client_id', auth()->id()]])
            ->exists();


        return [
            'offer_id' => [
                'required',
                Rule::exists('order_requests', 'id')->where(function ($query) {
                    $query->whereNotNull('price');
                }),
                'in:' . ($clientOrderOffer ? $this->offer_id : '0')
            ],
            'coupon_code' => [
                'required',
                'string',
                'max:250',
                'exists:partners',
                new UsedCoupon()
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'offer_id.exists' => __('messages.invalid_offer')
        ];
    }
}

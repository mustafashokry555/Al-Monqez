<?php

namespace App\Http\Requests\Api\StoreApp\Coupons;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Rules\StoreApp\UsedCoupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyCouponRequest extends FormRequest
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
            'store_id' => [
                'required',
                Rule::exists('store_carts', 'store_id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
            'coupon_code' => [
                'nullable',
                'string',
                'max:250',
                'exists:store_coupons,code',
                new UsedCoupon($this->store_id)
            ]
        ];
    }
}

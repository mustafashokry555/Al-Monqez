<?php

namespace App\Http\Requests\Admin\StoreApp\Coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteCouponRequest extends FormRequest
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
            'coupon_id' => 'required|exists:store_coupons,id',
        ];

        if (auth()->user()->role_id == 6) {
            $rules['coupon_id'] = [
                'required',
                Rule::exists('store_coupons', 'id')->where(function ($query) {
                    $query->where('store_id', auth()->id());
                }),
            ];
        }

        return $rules;
    }
}

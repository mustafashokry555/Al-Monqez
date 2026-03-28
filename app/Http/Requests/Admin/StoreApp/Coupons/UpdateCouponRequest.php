<?php

namespace App\Http\Requests\Admin\StoreApp\Coupons;

use App\Models\StoreCoupon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
        return array_merge(StoreCoupon::rules(), [
            'coupon_id' => 'required|exists:store_coupons,id',
            'coupon_code' => 'required|string|max:50|unique:store_coupons,code' . ($this->coupon_id ? ",$this->coupon_id" : ''),
        ]);
    }
}

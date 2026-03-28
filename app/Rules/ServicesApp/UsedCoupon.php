<?php

namespace App\Rules\ServicesApp;

use App\Models\Partner;
use App\Models\UserCoupon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsedCoupon implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = Partner::where('coupon_code', $value)->where('valid_from', '<=', now())->where('valid_until', '>=', now())->first();
        $usedCoupon = UserCoupon::join('orders', 'orders.id', '=', 'user_coupons.order_id')
            ->where([
                ['user_coupons.user_id', auth()->id()],
                ['user_coupons.coupon_id', $coupon?->id],
                ['orders.status', '!=', '4']
            ])
            ->exists();

        if (!$coupon || $usedCoupon) {
            $fail(__('messages.coupon_code_invalid'));
        }
    }
}

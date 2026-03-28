<?php

namespace App\Rules\StoreApp;

use App\Models\StoreCoupon;
use App\Models\StoreUsedCoupon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsedCoupon implements ValidationRule
{
    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coupon = StoreCoupon::where([
            ['store_id', $this->storeId],
            ['code', $value],
            ['valid_from', '<=', now()],
            ['valid_until', '>=', now()]
        ])
            ->first();
        $usedCoupon = StoreUsedCoupon::join('store_orders', 'store_orders.id', '=', 'store_used_coupons.order_id')
            ->where([
                ['store_used_coupons.user_id', auth()->id()],
                ['store_used_coupons.coupon_id', $coupon?->id],
                ['store_orders.status', '!=', '5']
            ])
            ->exists();

        if (!$coupon || $usedCoupon) {
            $fail(__('messages.coupon_code_invalid'));
        }
    }
}

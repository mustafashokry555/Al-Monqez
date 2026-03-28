<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\OrderHelper;
use App\Http\Requests\Api\StoreApp\Coupons\ApplyCouponRequest;
use App\Models\StoreCoupon;
use App\Models\StoreProduct;

class CouponController extends Controller
{
    use ApiResponse, OrderHelper;

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $coupon = StoreCoupon::select('discount_percentage', 'max_discount_amount')->where('code', $request->coupon_code)->first();
        $products = StoreProduct::select(
            'store_products.id',
            'store_products.price',
            'store_products.sale_price',
            'store_carts.quantity'
        )
            ->join('store_carts', 'store_carts.product_id', '=', 'store_products.id')
            ->where('store_products.store_id', $request->store_id)
            ->where([['store_products.displayed', '1']])
            ->where('store_carts.user_id', auth()->id())
            ->get();

        $orderSummary = $this->calcOrderSummary($products, $coupon);

        return $this->apiResponse(200, 'coupon applied', null, [
            'order_summary' => $orderSummary,
        ]);
    }
}

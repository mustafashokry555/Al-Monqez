<?php

namespace App\Http\Helpers;

use App\Models\StoreSetting;

trait OrderHelper
{
    public function calcOrderSummary($products, $coupon = null)
    {
        $orderSetting = StoreSetting::select('vat', 'delivery_charge')->first();
        $sub_total_price = 0;
        foreach ($products as $product) {
            $price = $product->sale_price ? $product->sale_price : $product->price;
            $sub_total_price += $price * $product->quantity;
        }

        $discount_amount = 0;
        if ($coupon) {
            $discount_amount = $coupon->discount_percentage / 100 * $sub_total_price;
            if ($discount_amount > $coupon->max_discount_amount) {
                $discount_amount = $coupon->max_discount_amount;
            }
        }
        $discounted_total = $sub_total_price - $discount_amount;
        $vat_amount = ($orderSetting->vat / 100) * $discounted_total;
        $total_price = $discounted_total + $vat_amount + $orderSetting->delivery_charge;

        return [
            'sub_total_price' => number_format((float)$sub_total_price, 2, '.', ''),
            'discount_amount' => number_format((float)$discount_amount, 2, '.', ''),
            'vat_percentage' => $orderSetting->vat,
            'vat_amount' => number_format((float)$vat_amount, 2, '.', ''),
            'delivery_charge' => $orderSetting->delivery_charge,
            'total_price' => number_format((float)$total_price, 2, '.', '')
        ];
    }
}

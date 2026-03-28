<?php

namespace App\Rules\StoreApp;

use App\Models\StoreProduct;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxQuantityRule implements ValidationRule
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = StoreProduct::select('quantity')->find($this->productId);

        if ($product && $value > $product->quantity) {
            $fail(__('messages.product_stock_exceeded', ['QUANTITY' => $product->quantity]));
        }
    }
}

<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\OrderHelper;
use App\Http\Requests\Api\StoreApp\Carts\AddCartRequest;
use App\Http\Requests\Api\StoreApp\Carts\RemoveCartRequest;
use App\Http\Resources\StoreApp\ProductResource;
use App\Http\Resources\StoreApp\StoreResource;
use App\Models\StoreCart;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use ApiResponse, OrderHelper;

    public function index()
    {
        $language = app()->getLocale();
        $stores = User::query()
            ->select(
                'users.id',
                'users.name',
                "store_details.address_$language AS address",
                'users.image'
            )
            ->join('store_details', 'store_details.store_id', '=', 'users.id')
            ->join('store_carts', 'store_carts.store_id', '=', 'users.id')
            ->where('store_carts.user_id', auth()->id())
            ->distinct()
            ->get();

        return $this->apiResponse(200, 'cart stores', null, [
            'stores' => StoreResource::collection($stores),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($store_id)
    {
        $language = app()->getLocale();
        $store = User::select(
            'users.id',
            'users.name',
            'users.image'
        )
            ->join('store_details', 'store_details.store_id', '=', 'users.id')
            ->where('users.id', $store_id)
            ->first();

        $products = StoreProduct::select(
            'store_products.id',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.sale_price',
            'store_products.patch_id',
            'store_products.quantity AS max_quantity',
            'store_carts.quantity'
        )
            ->join('store_carts', 'store_carts.product_id', '=', 'store_products.id')
            ->where('store_products.store_id', $store_id)
            ->where([['store_products.displayed', '1']])
            ->where('store_carts.user_id', '=', auth()->id())
            ->with('patch')
            ->get();

        $suggestedProducts = StoreProduct::select(
            'store_products.id',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.patch_id',
            'store_products.sale_price',
            'store_products.quantity AS max_quantity',
            DB::raw('0 AS quantity'),
        )
            ->leftJoin('store_carts', 'store_carts.product_id', '=', 'store_products.id')
            ->where('store_products.store_id', $store_id)
            ->where([['store_products.displayed', '1']])
            ->whereNull('store_carts.id')
            ->with('patch')
            ->limit(5)
            ->get();

        $orderSummary = $this->calcOrderSummary($products);

        return $this->apiResponse(200, 'cart store details', null, [
            'store' => new StoreResource($store),
            'products' => ProductResource::collection($products),
            'suggested_products' => ProductResource::collection($suggestedProducts),
            'order_summary' => $orderSummary
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function addToCart(AddCartRequest $request)
    {
        $product = StoreProduct::select('store_id')->find($request->product_id);
        StoreCart::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'store_id' => $product->store_id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return $this->apiResponse(200, __('messages.product_added_to_cart'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function removeFromCart(RemoveCartRequest $request)
    {
        StoreCart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->delete();

        return $this->apiResponse(200, __('messages.product_removed_from_cart'));
    }
}

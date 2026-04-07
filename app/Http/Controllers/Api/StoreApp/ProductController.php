<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\StoreApp\ProductResource;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $language = app()->getLocale();
        $products = StoreProduct::query()->select(
            'store_products.id',
            'store_products.patch_id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.sale_price',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            });

        if ($request->filled('classification_id')) {
            $products->where('store_products.classification_id', $request->classification_id);
        }

        if ($request->filled('search')) {
            $products->where(function ($q) use ($request, $language) {
                $q->where("store_products.name_$language", 'LIKE', "%{$request->search}%")
                    ->orWhere("store_products.description_$language", 'LIKE', "%{$request->search}%");
            });
        }

        $products = $products->where([['store_products.displayed', '1']])
            ->with('patch')
            ->orderBy('store_products.created_at', 'DESC')
            ->get();
        // return $products;
        return $this->apiResponse(200, 'products', null, [
            'products' => ProductResource::collection($products),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $product = StoreProduct::query()->select(
            'store_products.id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.sale_price',
            'store_products.quantity AS max_quantity',
            'store_products.visits',
            DB::raw('CASE WHEN store_carts.id IS NOT NULL THEN store_carts.quantity ELSE 0 END AS quantity'),
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->with([
                'images' => function ($query) {
                    $query->select('product_id', 'path');
                }
            ])
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->leftJoin('store_carts', function ($join) {
                $join->on('store_carts.product_id', '=', 'store_products.id')
                    ->where('store_carts.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_products.displayed', '1']])
            ->findOrFail($id);

        $product->increment('visits');

        return $this->apiResponse(200, 'product', null, [
            'product' => new ProductResource($product),
        ]);
    }
}

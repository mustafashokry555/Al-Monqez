<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\StoreApp\CategoryResource;
use App\Http\Resources\StoreApp\ProductResource;
use App\Http\Resources\StoreApp\StoreResource;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $language = app()->getLocale();
        $categories = StoreCategory::select('id', "name_$language AS name", 'image')
            ->withCount('stores')
            ->where([['displayed', '1']])
            ->orderBy('created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'categories', null, [
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        StoreCategory::findOrFail($id);
        $language = app()->getLocale();
        $stores = User::select(
            'users.id',
            'users.name',
            "store_details.address_$language AS address",
            'users.image'
        )
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->where([['users.role_id', '6'], ['store_details.category_id', $id], ['blocked', '0']])
            ->orderBy('users.created_at', 'DESC')
            ->limit(4)
            ->get();
        $mostRecentProducts = StoreProduct::select(
            'store_products.id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.patch_id',
            'store_products.price',
            'store_products.sale_price',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_details.category_id', $id], ['store_products.displayed', '1']])
            ->with('patch')
            ->orderBy('store_products.created_at', 'DESC')
            ->limit(8)
            ->get();
        $mostSaledProducts = StoreProduct::select(
            'store_products.id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.patch_id',
            'store_products.sale_price',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_details.category_id', $id], ['store_products.displayed', '1']])
            ->with('patch')
            ->limit(8)
            ->get();

        return $this->apiResponse(200, 'category-stores', null, [
            'stores' => StoreResource::collection($stores),
            'most_recent_products' => ProductResource::collection($mostRecentProducts),
            'most_saled_products' => ProductResource::collection($mostSaledProducts),
        ]);
    }
}

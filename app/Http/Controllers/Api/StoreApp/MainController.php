<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\StoreApp\CategoryResource;
use App\Http\Resources\StoreApp\ProductResource;
use App\Http\Resources\StoreApp\SliderResource;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\StoreSlider;
use App\Models\StoreSetting;

use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    use ApiResponse;

    public function homeData()
    {
        $language = app()->getLocale();
        $sliders = StoreSlider::select('id', 'link', 'image')->where([['displayed', '1']])->get();
        $categories = StoreCategory::select('id', "name_$language AS name", 'image')
            ->withCount('stores')
            ->where([['displayed', '1']])
            ->orderBy('created_at', 'DESC')
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
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_products.displayed', '1']])
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
            'store_products.patch_id',
            'store_products.price',
            'store_products.sale_price',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_products.displayed', '1']])
            ->with('patch')
            ->limit(8)
            ->get();

        // Most visited products (based on visits column)
        $mostVisitedProducts = StoreProduct::select(
            'store_products.id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.patch_id',
            'store_products.sale_price',
            'store_products.visits',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.product_id', '=', 'store_products.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['store_products.displayed', '1']])
            ->with('patch')
            ->orderByDesc('store_products.visits')
            ->limit(8)
            ->get();

        return $this->apiResponse(200, 'home data', null, [
            'sliders' => SliderResource::collection($sliders),
            'categories' => CategoryResource::collection($categories),
            'most_recent_products' => ProductResource::collection($mostRecentProducts),
            'most_saled_products' => ProductResource::collection($mostSaledProducts),
            'most_visited_products' => ProductResource::collection($mostVisitedProducts)
        ]);
    }

    public function driverTerms()
    {
        $language = app()->getLocale();
        $terms = StoreSetting::select("driver_terms_$language AS driver_terms")
            ->first();

        return $this->apiResponse(200, 'terms', null, [
            'terms' => $terms
        ]);
    }
}

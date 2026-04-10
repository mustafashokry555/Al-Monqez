<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\StoreApp\ProductResource;
use App\Http\Resources\StoreApp\StoreResource;
use App\Models\StoreCategory;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    use ApiResponse;

    public function index($categoryId, Request $request)
    {
        StoreCategory::findOrFail($categoryId);
        $language = app()->getLocale();
        $stores = User::query()->select(
            'users.id',
            'users.name',
            "store_details.address_$language AS address",
            'users.image'
        )
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->where([['users.role_id', '6'], ['store_details.category_id', $categoryId], ['blocked', '0']]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $stores->where('users.name', 'LIKE', "%$search%");
        }

        $stores = $stores->orderBy('users.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'stores', null, [
            'stores' => StoreResource::collection($stores),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $store = User::select(
            'users.id',
            'users.name',
            "store_details.address_$language AS address",
            'users.image',
            'store_details.cover_image',
            'users.rating',
            DB::raw('CASE WHEN store_favorites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favorite')
        )
            ->with([
                'classifications' => function ($query) use ($language) {
                    $query->select('id', 'store_id', "name_$language AS name");
                }
            ])
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->leftJoin('store_favorites', function ($join) {
                $join->on('store_favorites.store_id', '=', 'users.id')
                    ->where('store_favorites.user_id', '=', auth()->id() ?? 0);
            })
            ->where([['users.role_id', '6'], ['blocked', '0']])
            ->findOrFail($id);

        $products = StoreProduct::select(
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
            ->where([['store_products.store_id', $id], ['store_products.displayed', '1']])
            ->with('patch')
            ->orderBy('store_products.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'store', null, [
            'store' => new StoreResource($store),
            'products' => ProductResource::collection($products),
        ]);
    }
}

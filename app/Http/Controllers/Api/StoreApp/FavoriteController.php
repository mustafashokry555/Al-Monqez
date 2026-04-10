<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\StoreApp\Favorites\ToggleFavoriteProductRequest;
use App\Http\Requests\Api\StoreApp\Favorites\ToggleFavoriteStoreRequest;
use App\Http\Resources\StoreApp\ProductResource;
use App\Http\Resources\StoreApp\StoreResource;
use App\Models\StoreFavorite;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function favoriteStores()
    {
        $language = app()->getLocale();
        $favorites = User::query()->select(
            'users.id',
            'users.name',
            "store_details.address_$language AS address",
            'users.image',
            DB::raw('1 AS is_favorite')
        )
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->join('store_favorites', 'store_favorites.store_id', '=', 'users.id')
            ->where([['users.role_id', '6'], ['blocked', '0']])
            ->where('store_favorites.user_id', '=', auth()->id())
            ->orderBy('store_favorites.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'favorite stores', null, [
            'favorites' => StoreResource::collection($favorites),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function toggleFavoriteStore(ToggleFavoriteStoreRequest $request)
    {
        $favorite = StoreFavorite::firstOrCreate([
            'user_id' => auth()->id(),
            'store_id' => $request->store_id,
        ]);

        if (!$favorite->wasRecentlyCreated) {
            $favorite->delete();

            return $this->apiResponse(200, __('messages.store_removed_from_favorites'), null, [
                'is_favorite' => 0,
            ]);
        }

        return $this->apiResponse(200, __('messages.store_added_to_favorites'), null, [
            'is_favorite' => 1,
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function favoriteProducts()
    {
        $language = app()->getLocale();
        $favorites = StoreProduct::query()->select(
            'store_products.id',
            'users.name as store_name',
            "store_products.name_$language AS name",
            "store_products.description_$language AS description",
            'store_products.image',
            'store_products.price',
            'store_products.sale_price',
            'store_products.patch_id',
            DB::raw('1 AS is_favorite')
        )
            ->join('store_favorites', 'store_favorites.product_id', '=', 'store_products.id')
            ->join('users', 'users.id', '=', 'store_products.store_id')
            ->where('store_favorites.user_id', auth()->id())
            ->where([['store_products.displayed', '1']])
            ->with('patch')
            ->orderBy('store_favorites.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'favorite products', null, [
            'products' => ProductResource::collection($favorites),
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function toggleFavoriteProduct(ToggleFavoriteProductRequest $request)
    {
        $favorite = StoreFavorite::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        if (!$favorite->wasRecentlyCreated) {
            $favorite->delete();

            return $this->apiResponse(200, __('messages.product_removed_from_favorites'), null, [
                'is_favorite' => 0,
            ]);
        }

        return $this->apiResponse(200, __('messages.product_added_to_favorites'), null, [
            'is_favorite' => 1,
        ]);
    }
}

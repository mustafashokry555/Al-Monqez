<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\ServicesApp\CategoryResource;
use App\Http\Resources\ServicesApp\SliderResource;
use App\Http\Resources\ServicesApp\SubCategoryResource;
use App\Models\Category;
use App\Models\Chat;
use App\Models\City;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Slider;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class MainController extends Controller
{
    use ApiResponse;

    public function homeData()
    {
        $language = app()->getLocale();
        $sliders = Slider::select('link', 'image')->where([['displayed', '1']])->get();

        $categories = Category::select('id', "name_$language AS name", 'image')
            ->where([['displayed', '1']])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        $haveNotifications = 0;
        if (auth()->user()) {
            $haveNotifications = (Notification::where([['user_id', auth()->id()], ['read', 0]])
                ->whereHasMorph('notifiable', [Order::class])
                ->exists() ||
                Chat::join('orders', 'orders.id', '=', 'chats.order_id')
                ->join('messages', 'messages.chat_id', '=', 'chats.id')
                ->where(function ($query) {
                    $query->where('orders.worker_id', auth()->id())
                        ->orWhere('orders.client_id', auth()->id());
                })
                ->where([['messages.read', 0], ['messages.user_id', '!=', auth()->id()]])
                ->exists()) ? 1 : 0;
        }

        return $this->apiResponse(200, 'home data', null, [
            'sliders' => SliderResource::collection($sliders),
            'categories' => CategoryResource::collection($categories),
            'have_notifications' => $haveNotifications
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function cities()
    {
        $language = app()->getLocale();
        $cities = City::select('id', "name_$language AS name")
            ->where([['displayed', '1']])
            ->orderBy('created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'cities', null, [
            'cities' => $cities
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function citiesAndCategories()
    {
        $language = app()->getLocale();
        $cities = City::select('id', "name_$language AS name")
            ->where([['displayed', '1']])
            ->orderBy('name', 'ASC')
            ->get();

        $categories = Category::select('id', "name_$language AS name")
            ->where([['displayed', '1']])
            ->orderBy('name', 'ASC')
            ->get();

        return $this->apiResponse(200, 'cities and categories', null, [
            'cities' => $cities,
            'categories' => $categories
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function subCategories(Request $request)
    {
        $language = app()->getLocale();
        $subCategories = SubCategory::query()->select(
            'sub_categories.id',
            'sub_categories.sub_category_type',
            'sub_categories.location_type',
            "sub_categories.name_$language AS name",
            'sub_categories.image'
        );

        if ($request->filled('category_id')) {
            $subCategories->where('category_id', $request->category_id);
        } else if ($request->filled('order_id')) {
            $order = Order::select('worker_id', 'sub_categories.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'orders.sub_category_id')
                ->where('orders.id', $request->order_id)
                ->firstOrFail();

            if ($order) {
                $subCategories->join('user_sub_categories', 'user_sub_categories.sub_category_id', '=', 'sub_categories.id')
                    ->where('category_id', $order->category_id)
                    ->where('user_sub_categories.user_id', $order->worker_id);
            }
        }

        $subCategories = $subCategories->where([['displayed', '1']])
            ->orderBy('name', 'ASC')
            ->get();

        return $this->apiResponse(200, 'sub categories', null, [
            'sub_categories' => SubCategoryResource::collection($subCategories)
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function subCategoriesWithServices(Request $request)
    {
        $language = app()->getLocale();
        $subCategories = SubCategory::select('id', 'sub_category_type', "name_$language AS name", 'image')
            ->with('services', function ($query) use ($language) {
                $query->select('id', 'sub_category_id', "name_$language AS name", 'image')
                    ->where([['displayed', '1']])
                    ->orderBy('name', 'ASC');
            })
            ->where('category_id', $request->category_id)
            ->where([['displayed', '1']])
            ->orderBy('name', 'ASC')
            ->get();

        return $this->apiResponse(200, 'sub categories with services', null, [
            'sub_categories' => SubCategoryResource::collection($subCategories)
        ]);
    }
}

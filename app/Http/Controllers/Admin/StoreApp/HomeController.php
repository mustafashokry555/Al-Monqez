<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Models\StoreCategory as Category;
use App\Models\StoreClassification;
use App\Models\StoreCoupon;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();

        if (auth()->user()->role_id != '6') {
            $total_revenue = StoreOrder::where('status', 4)->sum(DB::raw('sub_total * management_ratio / 100'));

            $total_stores = User::where('role_id', 6)->count();
            $total_drivers = User::where('role_id', 5)->count();
            $total_categories = Category::count();

            $stores = User::where('role_id', 6)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at')->latest()->take(5)->get();
            $drivers = User::where('role_id', 5)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at')->latest()->take(5)->get();
        }

        $total_pended_orders = StoreOrder::query()->where('status', 0);
        $total_under_preparation_orders = StoreOrder::query()->where('status', 1);
        $total_prepared_orders = StoreOrder::query()->where('status', 2);
        $total_in_delivery_orders = StoreOrder::query()->where('status', 3);
        $total_delivered_orders = StoreOrder::query()->where('status', 4);
        $total_canceled_orders = StoreOrder::query()->where('status', 5);

        $total_classifications = StoreClassification::query();
        $total_products = StoreProduct::query();
        $total_coupons = StoreCoupon::query();

        $orders = StoreOrder::query()->select(
            'store_orders.id',
            'clients.name AS client_name',
            'users.name AS store_name',
            'drivers.name AS driver_name',
            'store_orders.address',
            'store_orders.latitude',
            'store_orders.longitude',
            'store_orders.management_ratio',
            'store_orders.vat',
            'store_orders.delivery_charge',
            'store_used_coupons.coupon_code',
            'store_used_coupons.discount_percentage',
            'store_used_coupons.max_discount_amount',
            'store_orders.sub_total',
            'store_orders.total',
            'store_orders.status',
            'store_orders.created_at',
        )
            ->join('users', 'users.id', '=', 'store_orders.store_id')
            ->leftJoin('users AS drivers', 'drivers.id', '=', 'store_orders.driver_id')
            ->join('users AS clients', 'clients.id', '=', 'store_orders.user_id')
            ->leftJoin('store_used_coupons', 'store_used_coupons.order_id', '=', 'store_orders.id');

        if (auth()->user()->role_id == '6') {
            $total_revenue = StoreOrder::leftJoin('store_used_coupons', 'store_orders.id', '=', 'store_used_coupons.order_id')
                ->where('store_orders.store_id', auth()->id())
                ->where('status', 4)
                ->sum(DB::raw('
                    store_orders.sub_total -
                    (CASE
                        WHEN store_used_coupons.id IS NULL
                        THEN 0
                        ELSE LEAST(
                            store_orders.sub_total * store_used_coupons.discount_percentage / 100,
                            store_used_coupons.max_discount_amount
                        )
                    END) -
                    (store_orders.sub_total * store_orders.management_ratio / 100)
            '));

            $total_pended_orders->where('store_id', auth()->id());
            $total_under_preparation_orders->where('store_id', auth()->id());
            $total_prepared_orders->where('store_id', auth()->id());
            $total_in_delivery_orders->where('store_id', auth()->id());
            $total_delivered_orders->where('store_id', auth()->id());
            $total_canceled_orders->where('store_id', auth()->id());

            $total_classifications->where('store_id', auth()->id());
            $total_products->where('store_id', auth()->id());
            $total_coupons->where('store_id', auth()->id());

            $orders->where('store_orders.store_id', auth()->id());
        }

        $total_pended_orders = $total_pended_orders->count();
        $total_under_preparation_orders = $total_under_preparation_orders->count();
        $total_prepared_orders = $total_prepared_orders->count();
        $total_in_delivery_orders = $total_in_delivery_orders->count();
        $total_delivered_orders = $total_delivered_orders->count();
        $total_canceled_orders = $total_canceled_orders->count();

        $total_classifications = $total_classifications->count();
        $total_products = $total_products->count();
        $total_coupons = $total_coupons->count();

        $orders = $orders->latest()->take(5)->get();

        return view('admin.store-app.index', get_defined_vars());
    }
}

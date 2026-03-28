<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        if (auth()->user()->role_id != '7') {
            $total_revenue = Order::where('status', 3)->sum(DB::raw('total * management_ratio / 100'));
            $total_companies = User::where('role_id', 7)->count();
            $total_pended_orders = Order::where('status', 0)->count();

            $total_categories = Category::count();
            $total_sub_categories = SubCategory::count();
            $total_services = Service::count();

            $companies = User::where('role_id', 7)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at')->latest()->take(5)->get();
        }

        $total_workers = User::query()->where('role_id', 3)->where('accepted', '1');
        $total_accepted_orders = Order::query()->where('status', 1);
        $total_in_process_orders = Order::query()->where('status', 2);
        $total_finished_orders = Order::query()->where('status', 3);
        $total_canceled_orders = Order::query()->where('status', 4);

        $workers = User::query()->where('role_id', 3)->select('id', 'name', 'phone', 'email', 'blocked', 'created_at');
        $orders = Order::query()->select(
            'orders.id',
            'clients.name AS client_name',
            'workers.name AS worker_name',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
            "cities.name_$language AS city_name",
            'orders.management_ratio',
            'orders.deposit_ratio',
            'orders.vat',
            'orders.total',
            'orders.date',
            'orders.time',
            'orders.status'
        )
            ->join('users AS clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('users AS workers', 'orders.worker_id', '=', 'workers.id')
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('cities', 'orders.city_id', '=', 'cities.id');

        if (auth()->user()->role_id == '7') {
            $total_revenue = DB::query()
                ->fromSub(function ($q) {
                    $q->from('orders')
                        ->leftJoin('user_coupons', 'user_coupons.order_id', '=', 'orders.id')
                        ->where('orders.status', 3)
                        ->where('orders.company_id', auth()->id())
                        ->selectRaw("
                            orders.management_ratio,
                            (
                                orders.total
                                - COALESCE(
                                    LEAST(
                                        orders.total * user_coupons.discount_percentage / 100,
                                        user_coupons.max_discount_amount
                                    ),
                                    0
                                )
                            ) AS discounted_total
                        ");
                }, 'o')
                ->selectRaw("
                    SUM(
                        o.discounted_total
                        - (o.discounted_total * o.management_ratio / 100)
                    ) AS total_worker_revenue
                ")
                ->value('total_worker_revenue') ?? 0;


            $total_workers->where('company_id', auth()->id());
            $total_accepted_orders->where('company_id', auth()->id());
            $total_in_process_orders->where('company_id',  auth()->id());
            $total_finished_orders->where('company_id',  auth()->id());
            $total_canceled_orders->where('company_id',  auth()->id());

            $workers->where('company_id', auth()->id());
            $orders->where('orders.company_id', auth()->id());
        }

        $total_workers = $total_workers->count();
        $total_accepted_orders = $total_accepted_orders->count();
        $total_in_process_orders = $total_in_process_orders->count();
        $total_finished_orders = $total_finished_orders->count();
        $total_canceled_orders = $total_canceled_orders->count();

        $workers = $workers->latest()->take(5)->get();
        $orders = $orders->orderBy('orders.created_at', 'DESC')->take(5)->get();

        return view('admin.services-app.index', get_defined_vars());
    }
}

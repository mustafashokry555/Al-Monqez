<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Orders\AssignWorkerRequest;
use App\Http\Requests\Admin\ServicesApp\Orders\DeleteOrderRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $language = app()->getLocale();
        $workers = [];
        $orders = Order::query()->select(
            'orders.id',
            'orders.payment_type',
            'orders.transaction_id',
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

        if ($request->filled('status')) {
            $orders->where('orders.status', $request->status);
        }

        if (auth()->user()->role_id == '7') {
            $workers = User::select('id', 'name')
                ->where('company_id', auth()->id())
                ->where('role_id', '3')
                ->get();
            $orders->where('orders.company_id', auth()->id());
        }

        $orders = $orders->orderBy('orders.created_at', 'desc')
            ->paginate(10);

        return view('admin.services-app.orders.index', compact('workers', 'orders'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $order = Order::query()->select(
            'orders.*',
            'clients.name AS client_name',
            'clients.phone AS client_phone',
            'clients.image AS client_image',
            'workers.name AS worker_name',
            'workers.phone AS worker_phone',
            'workers.image AS worker_image',
            'workers.rating AS worker_rating',
            "cities.name_$language AS city_name",
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
            'user_coupons.coupon_code',
            'user_coupons.discount_percentage',
            'user_coupons.max_discount_amount'
        )
            ->with([
                'services' => function ($query) use ($language) {
                    $query->select('order_services.order_id', "services.name_$language AS name", 'services.image')
                        ->join('services', 'order_services.service_id', '=', 'services.id');
                },
                'locations' => function ($query) {
                    $query->select('order_id', 'type', 'title', 'latitude', 'longitude');
                },
                'problemImages' => function ($query) {
                    $query->select('order_id', 'image');
                },
                'beforeImages' => function ($query) {
                    $query->select('order_id', 'image');
                },
                'afterImages' => function ($query) {
                    $query->select('order_id', 'image');
                }
            ])
            ->join('users AS clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('users AS workers', 'orders.worker_id', '=', 'workers.id')
            ->join('cities', 'orders.city_id', '=', 'cities.id')
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->leftJoin('user_coupons', 'orders.id', '=', 'user_coupons.order_id');

        if (auth()->user()->role_id == '7') {
            $order->where('orders.company_id', auth()->id());
        }

        $order = $order->findOrFail($id);

        return view('admin.services-app.orders.show', compact('order'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function assignWorker(AssignWorkerRequest $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->update([
            'worker_id' => $request->worker_id,
        ]);

        session()->flash('success', __('messages.assign_worker_to_order'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(DeleteOrderRequest $request)
    {
        Order::findOrFail($request->order_id)->delete();

        session()->flash('success', __('messages.delete_order'));
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApp\Orders\DeleteOrderRequest;
use App\Http\Requests\Admin\StoreApp\Orders\ProcessOrderRequest;
use App\Models\StoreOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
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

        if ($request->filled('status')) {
            $orders->where('store_orders.status', $request->status);
        }

        if (auth()->user()->role_id == '6') {
            $orders->where('store_orders.store_id', auth()->id());
        }

        $orders = $orders->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.store-app.orders.index', compact('orders'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $order = StoreOrder::query()->select(
            'store_orders.*',
            'users.name AS store_name',
            'users.phone AS store_phone',
            'users.image AS store_image',
            'users.rating AS store_rating',
            'store_details.address_' . $language . ' as store_address',
            'store_details.latitude AS store_latitude',
            'store_details.longitude AS store_longitude',
            'clients.name AS client_name',
            'clients.phone AS client_phone',
            'clients.image AS client_image',
            'drivers.name AS driver_name',
            'drivers.phone AS driver_phone',
            'drivers.image AS driver_image',
            'store_used_coupons.coupon_code',
            'store_used_coupons.discount_percentage',
            'store_used_coupons.max_discount_amount',
        )
            ->with([
                'products' => function ($query) use ($language) {
                    $query->select(
                        'store_products.id',
                        'store_order_products.order_id',
                        "store_products.name_$language AS name",
                        'store_products.image',
                        'store_order_products.quantity',
                        'store_order_products.price'
                    )
                        ->join('store_products', 'store_order_products.product_id', '=', 'store_products.id');
                },
            ])
            ->join('users', 'users.id', '=', 'store_orders.store_id')
            ->join('users AS clients', 'clients.id', '=', 'store_orders.user_id')
            ->leftJoin('users AS drivers', 'drivers.id', '=', 'store_orders.driver_id')
            ->leftJoin('store_used_coupons', 'store_used_coupons.order_id', '=', 'store_orders.id')
            ->join('store_details', 'store_details.store_id', '=', 'users.id');

        if (auth()->user()->role_id == '6') {
            $order->where('store_orders.store_id', auth()->id());
        }

        $order = $order->findOrFail($id);

        return view('admin.store-app.orders.show', compact('order'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function process(ProcessOrderRequest $request)
    {
        $order = StoreOrder::findOrFail($request->order_id);
        $order->update([
            'status' => ++$order->status
        ]);

        session()->flash('success', __('messages.change_order_status'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(DeleteOrderRequest $request)
    {
        StoreOrder::findOrFail($request->order_id)->delete();

        session()->flash('success', __('messages.delete_order'));
        return redirect()->back();
    }
}

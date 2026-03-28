<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Models\OrderLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        return view('admin.services-app.maps.index');
    }

    public function locations()
    {
        $orders = OrderLocation::select(
            "order_locations.order_id AS id",
            DB::raw("CONCAT('" . __('admin.order') . " ', order_locations.order_id) AS name"),
            'order_locations.type',
            'order_locations.latitude',
            'order_locations.longitude'
        )
            ->join('orders', 'order_locations.order_id', '=', 'orders.id')
            ->where('orders.status', '<', 3);

        $workers = User::select(
            "user_locations.user_id AS id",
            'users.name',
            DB::raw("2 AS type"),
            'user_locations.latitude',
            'user_locations.longitude'
        )
            ->join('user_locations', 'user_locations.user_id', '=', 'users.id')
            ->join('user_activity_logs', 'users.id', '=', 'user_activity_logs.user_id')
            ->where([['users.role_id', 3], ['user_activity_logs.is_online', 1]]);

        $locations = $orders->unionAll($workers)->get();

        return response()->json($locations);
    }
}

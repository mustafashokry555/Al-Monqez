<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Offers\MakeOfferRequest;
use App\Models\OrderRequest;

class OfferController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $offers = OrderRequest::select(
            'order_requests.id',
            'order_requests.order_id',
            'order_requests.price',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
            'cities.name_en AS city_name',
            'orders.date',
            'orders.time',
            'users.name AS client_name',
            'users.image AS client_image'
        )
            ->join('orders', 'order_requests.order_id', '=', 'orders.id')
            ->join('users', 'orders.client_id', '=', 'users.id')
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('cities', 'orders.city_id', '=', 'cities.id')
            ->where([['orders.status', '0'], ['order_requests.worker_id', auth()->id()]])
            ->paginate(10);

        return view('admin.services-app.offers.index', compact('offers'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function make(MakeOfferRequest $request)
    {
        $request->order_request->update([
            'price' => $request->price
        ]);

        session()->flash('success', __('messages.send_offer'));
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApp\Coupons\AddCouponRequest;
use App\Http\Requests\Admin\StoreApp\Coupons\DeleteCouponRequest;
use App\Http\Requests\Admin\StoreApp\Coupons\UpdateCouponRequest;
use App\Models\StoreCoupon;
use App\Models\User;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = StoreCoupon::query()->select(
            'store_coupons.*',
            'users.name AS store_name'
        )
            ->leftJoin('users', 'store_coupons.store_id', '=', 'users.id');

        if (auth()->user()->role_id == 6) {
            $coupons->where('store_coupons.store_id', auth()->id());
        }

        $coupons = $coupons->paginate(10);

        return view('admin.store-app.coupons.index', compact('coupons'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $stores = User::query()
            ->select('id', 'name')
            ->where('role_id', '6');

        if (auth()->user()->role_id == 6) {
            $stores->where('id', auth()->id());
        }

        $stores = $stores->get();

        return view('admin.store-app.coupons.create', compact('stores'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(AddCouponRequest $request)
    {
        StoreCoupon::create([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'code' => $request->coupon_code,
            'discount_percentage' => $request->discount_percentage,
            'max_discount_amount' => $request->max_discount_amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until
        ]);

        session()->flash('success', __('messages.add_coupon'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $coupon = StoreCoupon::query();
        $stores = User::query()->select('id', 'name')->where('role_id', '6');

        if (auth()->user()->role_id == 6) {
            $coupon->where('store_id', auth()->id());
            $stores->where('id', auth()->id());
        }

        $coupon = $coupon->findOrFail($id);
        $stores = $stores->get();

        return view('admin.store-app.coupons.edit', compact('coupon', 'stores'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function update(UpdateCouponRequest $request)
    {
        $coupon = StoreCoupon::findOrFail($request->coupon_id);

        $coupon->update([
            'store_id' => $request->store_id,
            'name' => $request->name,
            'code' => $request->coupon_code,
            'discount_percentage' => $request->discount_percentage,
            'max_discount_amount' => $request->max_discount_amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until
        ]);

        session()->flash('success', __('messages.update_coupon'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(DeleteCouponRequest $request)
    {
        StoreCoupon::findOrFail($request->coupon_id)->delete();

        session()->flash('success', __('messages.delete_coupon'));
        return redirect()->back();
    }
}

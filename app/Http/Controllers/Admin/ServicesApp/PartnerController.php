<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Partners\AddPartnerRequest;
use App\Http\Requests\Admin\ServicesApp\Partners\DeletePartnerRequest;
use App\Http\Requests\Admin\ServicesApp\Partners\UpdatePartnerRequest;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::paginate(10);

        return view('admin.services-app.partners.index', compact('partners'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.services-app.partners.create');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(AddPartnerRequest $request)
    {
        Partner::create([
            'name' => $request->name,
            'coupon_code' => $request->coupon_code,
            'discount_percentage' => $request->discount_percentage,
            'max_discount_amount' => $request->max_discount_amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until
        ]);

        session()->flash('success', __('messages.add_partner'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);

        return view('admin.services-app.partners.edit', compact('partner'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function update(UpdatePartnerRequest $request)
    {
        $partner = Partner::findOrFail($request->partner_id);

        $partner->update([
            'name' => $request->name,
            'coupon_code' => $request->coupon_code,
            'discount_percentage' => $request->discount_percentage,
            'max_discount_amount' => $request->max_discount_amount,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until
        ]);

        session()->flash('success', __('messages.update_partner'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(DeletePartnerRequest $request)
    {
        Partner::findOrFail($request->partner_id)->delete();

        session()->flash('success', __('messages.delete_partner'));
        return redirect()->back();
    }
}

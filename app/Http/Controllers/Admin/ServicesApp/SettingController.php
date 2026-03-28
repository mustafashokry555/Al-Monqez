<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Settings\AddOrderSettingRequest;
use App\Models\OrderSetting;
use App\Models\Category;
use App\Models\WarrantyCategory;

class SettingController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $orderSetting = OrderSetting::first();
        $categories = Category::select('id', "name_$language AS name")->get();
        $selected = WarrantyCategory::pluck('category_id')->toArray();
        return view('admin.services-app.settings.index', compact('orderSetting', 'categories', 'selected'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(AddOrderSettingRequest $request)
    {
        $orderSetting = OrderSetting::first();

        if ($orderSetting) {

            WarrantyCategory::query()->delete();

            if ($request->categories) {
                foreach ($request->categories as $categoryId) {
                    WarrantyCategory::create([
                        'category_id' => $categoryId
                    ]);
                }
            }

            $orderSetting->update([
                'warranty_days' => $request->warranty_days,
                'management_ratio' => $request->management_ratio,
                'deposit_ratio' => $request->deposit_ratio,
                'vat' => $request->vat,
                'start_time' => date('H:00', strtotime($request->start_time)),
                'end_time' => date('H:00', strtotime($request->end_time))
            ]);
        } else {
            $orderSetting = OrderSetting::create([
                'warranty_days' => $request->warranty_days,
                'management_ratio' => $request->management_ratio,
                'deposit_ratio' => $request->deposit_ratio,
                'vat' => $request->vat,
                'start_time' => date('H:00', strtotime($request->start_time)),
                'end_time' => date('H:00', strtotime($request->end_time))
            ]);
        }

        session()->flash('success', __('messages.change_settings'));
        return redirect()->back();
    }
}

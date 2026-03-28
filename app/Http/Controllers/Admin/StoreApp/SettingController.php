<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApp\Settings\AddStoreSettingRequest;
use App\Models\StoreSetting;

class SettingController extends Controller
{
    public function index()
    {
        $storeSetting = StoreSetting::first();
        $languages = ['ar', 'en', 'ur'];
        return view('admin.store-app.settings.index', compact('storeSetting', 'languages'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(AddStoreSettingRequest $request)
    {
        $storeSetting = StoreSetting::first();

        if ($storeSetting) {
            $languages = ['ar', 'en', 'ur'];

            foreach ($languages as $lang) {
                $storeSetting["store_terms_$lang"] = $request->input("store_terms_$lang");
                $storeSetting["driver_terms_$lang"] = $request->input("driver_terms_$lang");
            }

            $storeSetting->update([
                'management_ratio' => $request->management_ratio,
                'vat' => $request->vat,
                'delivery_charge' => $request->delivery_charge
            ]);
        } else {
            $storeSetting = StoreSetting::create([
                'management_ratio' => $request->management_ratio,
                'vat' => $request->vat,
                'delivery_charge' => $request->delivery_charge
            ]);
        }

        session()->flash('success', __('messages.change_settings'));
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\Dashboard\Settings\AddSettingRequest;
use App\Http\Requests\Admin\ServicesApp\Settings\AddOrderSettingRequest;
use App\Models\OrderSetting;
use App\Models\Setting;

class SettingController extends Controller
{
    use FileStorage;

    public function index()
    {
        $languages = ['ar', 'en', 'ur'];

        return view('admin.dashboard.settings.index', compact('languages'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function store(AddSettingRequest $request)
    {
        $setting = Setting::first();
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
            $data["closed_message_$lang"] = $request->input("closed_message_$lang");
        }

        if ($setting) {
            $data = array_merge($data, [
                'phone' => $request->phone,
                'email' => $request->email,
                'site_status' => ($request->site_status) ? 1 : 0,
                'logo' => $this->uploadFile($request, 'settings', $setting, 'logo', 'logo'),
                'store_image' => $this->uploadFile($request, 'settings', $setting, 'store_image', 'store_image'),
                'services_image' => $this->uploadFile($request, 'settings', $setting, 'services_image', 'services_image'),
                'android_app_link' => $request->android_app_link,
                'ios_app_link' => $request->ios_app_link,
                'registration_link' => $request->registration_link,
                'app_version' => $request->app_version
            ]);

            $setting->update($data);
        } else {
            $data = array_merge($data, [
                'phone' => $request->phone,
                'email' => $request->email,
                'site_status' => ($request->site_status) ? 1 : 0,
                'logo' => $this->uploadFile($request, 'settings', null, 'logo'),
                'store_image' => $this->uploadFile($request, 'settings', null, 'store_image'),
                'services_image' => $this->uploadFile($request, 'settings', null, 'services_image'),
                'android_app_link' => $request->android_app_link,
                'ios_app_link' => $request->ios_app_link,
                'registration_link' => $request->registration_link,
                'app_version' => $request->app_version
            ]);

            $setting = Setting::create($data);
        }

        session()->flash('success', __('messages.change_settings'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function order()
    {
        $orderSetting = OrderSetting::first();

        return view('admin.dashboard.settings.order', compact('orderSetting'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function orderStore(AddOrderSettingRequest $request)
    {
        $orderSetting = OrderSetting::first();

        if ($orderSetting) {
            $orderSetting->update([
                'management_ratio' => $request->management_ratio,
                'deposit_ratio' => $request->deposit_ratio,
                'vat' => $request->vat,
                'start_time' => date('H:00', strtotime($request->start_time)),
                'end_time' => date('H:00', strtotime($request->end_time))
            ]);
        } else {
            $orderSetting = OrderSetting::create([
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

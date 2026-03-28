<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApp\Cities\AddCityRequest;
use App\Http\Requests\Admin\StoreApp\Cities\UpdateCityRequest;
use App\Http\Requests\Admin\StoreApp\Cities\ValidateCityRequest;
use App\Models\StoreCity as City;

class CityController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $cities = City::select('id', "name_$language AS name", 'displayed')->paginate(10);

        return view('admin.store-app.cities.index', compact('cities'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];

        return view('admin.store-app.cities.create', compact('languages'));
    }

    public function store(AddCityRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'displayed' => ($request->show) ? 1 : 0
        ]);

        City::create($data);

        session()->flash('success', __('messages.add_city'));
        return redirect(route('store_app.admin.cities.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $languages = ['ar', 'en', 'ur'];

        return view('admin.store-app.cities.edit', compact('city', 'languages'));
    }

    public function update(UpdateCityRequest $request)
    {
        $city = City::findOrFail($request->city_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $city->update($data);

        session()->flash('success', __('messages.edit_city'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateCityRequest $request)
    {
        $city = City::findOrFail($request->city_id);

        $city->update([
            'displayed' => ($city->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($city->displayed == 1) ? __('messages.show_city') : __('messages.hide_city'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateCityRequest $request)
    {
        City::findOrFail($request->city_id)->delete();

        session()->flash('success', __('messages.delete_city'));
        return redirect()->back();
    }
}

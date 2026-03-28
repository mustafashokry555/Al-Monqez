<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Regions\ValidateRegionRequest;
use App\Models\Region;

class RegionController extends Controller
{
    public function edit()
    {
        $region = Region::first();

        return view('admin.services-app.regions.form', compact('region'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function update(ValidateRegionRequest $request)
    {
        Region::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Main Region',
            'coordinates' => $request->coordinates
        ]);

        session()->flash('success', __('messages.update_region'));
        return redirect()->back();
    }
}

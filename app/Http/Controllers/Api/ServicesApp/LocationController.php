<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\ServicesApp\Locations\AddLocationRequest;
use App\Http\Requests\Api\ServicesApp\Locations\DeleteLocationRequest;
use App\Http\Requests\Api\ServicesApp\Locations\UpdateUserLocationRequest;
use App\Http\Resources\ServicesApp\LocationResource;
use App\Models\Location;
use App\Models\UserLocation;

class LocationController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $locations = Location::select('id', 'title', 'latitude', 'longitude')
            ->where('user_id', auth()->id())
            ->get();

        return $this->apiResponse(200, 'locations', null, LocationResource::collection($locations));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function add(AddLocationRequest $request)
    {
        Location::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return $this->apiResponse(200, __('messages.add_location'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function delete(DeleteLocationRequest $request)
    {
        Location::findOrFail($request->location_id)->delete();

        return $this->apiResponse(200, __('messages.delete_location'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function updateUserLocation(UpdateUserLocationRequest $request)
    {
        UserLocation::updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'user_id' => auth()->id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return $this->apiResponse(200, __('messages.update_location'));
    }
}

<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\StoreApp\Stores\AddStoreRequest;
use App\Http\Requests\Admin\StoreApp\Stores\UpdateStoreRequest;
use App\Http\Requests\Admin\StoreApp\Stores\ValidateStoreRequest;
use App\Models\StoreCategory;
use App\Models\StoreCity;
use App\Models\StoreDetail;
use App\Models\User;
use App\Services\StoreService;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $stores = User::select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image',
            'users.rating',
            "store_cities.name_$language as city_name",
            "store_categories.name_$language as category_name",
            'store_details.address_' . $language . ' as address',
            'wallets.balance',
            'users.blocked',
            'users.created_at'
        )
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->join('store_cities', 'store_details.city_id', '=', 'store_cities.id')
            ->join('store_categories', 'store_details.category_id', '=', 'store_categories.id')
            ->where('role_id', '6')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('admin.store-app.stores.index', compact('stores'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];
        $language = app()->getLocale();
        $cities = StoreCity::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $categories = StoreCategory::select('id', "name_$language AS name")->where('displayed', 1)->get();

        return view('admin.store-app.stores.create', compact('cities', 'categories', 'languages'));
    }

    public function store(AddStoreRequest $request)
    {
        $storeService = new StoreService();

        $success = $storeService->create($request);

        if (!$success) {
            session()->flash('error', __('messages.something_went_wrong'));
        } else {
            session()->flash('success', __('messages.create_store'));
        }

        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $languages = ['ar', 'en', 'ur'];
        $language = app()->getLocale();
        $store = User::select(
            'users.*',
            'store_details.city_id',
            'store_details.category_id',
            'store_details.address_ar',
            'store_details.address_en',
            'store_details.address_ur',
            'store_details.latitude',
            'store_details.longitude',
            'store_details.cover_image',
            'store_details.commercial_registration',
            'store_details.license',
            'store_details.bank_name',
            'store_details.account_holder_name',
            'store_details.IBAN'
        )
            ->join('store_details', 'users.id', '=', 'store_details.store_id')
            ->where('role_id', '6')
            ->findOrFail($id);
        $cities = StoreCity::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $categories = StoreCategory::select('id', "name_$language AS name")->where('displayed', 1)->get();

        return view('admin.store-app.stores.edit', compact('store', 'cities', 'categories', 'languages'));
    }

    public function update(UpdateStoreRequest $request)
    {
        $store = User::findOrFail($request->store_id);

        $store->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email ?? $store->email,
            'password' => ($request->password) ? Hash::make($request->password) : $store->password,
            'image' => $this->uploadFile($request, 'stores', $store)
        ]);

        $storeDetail = StoreDetail::where('store_id', $store->id)->first();
        $storeDetail->update([
            'city_id' => $request->city_id,
            'category_id' => $request->category_id,
            'address_ar' => $request->address_ar,
            'address_en' => $request->address_en,
            'address_ur' => $request->address_ur,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
             'cover_image' => $this->uploadFile($request, 'store_covers', $storeDetail, 'cover_image', 'cover_image'),
            'commercial_registration' => $this->uploadFile($request, 'stores', null, 'commercial_registration', 'commercial_registration'),
            'license' => $this->uploadFile($request, 'stores', null, 'license', 'license'),
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'IBAN' => $request->IBAN
        ]);

        session()->flash('success', __('messages.edit_store'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function verify(ValidateStoreRequest $request)
    {
        $store = User::findOrFail($request->store_id);

        $store->update([
            'blocked' => ($store->blocked == '1') ? 0 : 1
        ]);

        session()->flash('success', ($store->blocked == '1') ? __('messages.deactivate_store') : __('messages.activate_store'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateStoreRequest $request)
    {
        $store = User::findOrFail($request->store_id);

        $store->forceDelete();
        $this->deleteFile($store->image);

        session()->flash('success', __('messages.delete_store'));
        return redirect()->back();
    }
}

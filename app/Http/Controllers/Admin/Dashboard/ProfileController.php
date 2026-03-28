<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\Dashboard\Profile\UpdateProfileRequest;
use App\Models\StoreCategory;
use App\Models\StoreCity;
use App\Models\StoreDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use FileStorage;

    public function index()
    {
        $languages = ['ar', 'en', 'ur'];
        $language = app()->getLocale();
        $cities = StoreCity::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $categories = StoreCategory::select('id', "name_$language AS name")->where('displayed', 1)->get();

        $storeDetail = null;
        if (auth()->user()->role_id == 6) {
            $storeDetail = StoreDetail::where('store_id', auth()->id())->first();
        }

        return view('admin.dashboard.profile.index', compact('languages', 'storeDetail', 'cities', 'categories'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function update(UpdateProfileRequest $request)
    {
        $user = User::find(auth()->id());
        $password = $user->password;
        if ($request->filled('password')) {
            $password = Hash::make($request->password);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $password,
            'image' => $this->uploadFile($request, 'users', $user)
        ]);

        if (auth()->user()->role_id == 6) {
            $storeDetail = StoreDetail::where('store_id', auth()->id())->first();
            $storeDetail->update([
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'address_ar' => $request->address_ar,
                'address_en' => $request->address_en,
                'address_ur' => $request->address_ur,
                'cover_image' => $this->uploadFile($request, 'store_covers', $storeDetail, 'cover_image', 'cover_image'),
            'commercial_registration' => $this->uploadFile($request, 'stores', $storeDetail, 'commercial_registration', 'commercial_registration'),
                'license' => $this->uploadFile($request, 'stores', $storeDetail, 'license', 'license'),
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'IBAN' => $request->IBAN
            ]);
        }

        session()->flash('success', __('messages.profile_updated'));
        return redirect()->back();
    }
}

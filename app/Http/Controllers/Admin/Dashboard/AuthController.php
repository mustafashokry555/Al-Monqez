<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dashboard\Auth\LoginRequest;
use App\Http\Requests\Admin\Dashboard\Auth\SignupRequest;
use App\Models\StoreCategory;
use App\Models\StoreCity;
use App\Services\StoreService;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreSetting;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function signIn(LoginRequest $request)
    {
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            if (auth()->user()->role_id == '6') {
                return redirect()->route('store_app.admin');
            } else if (auth()->user()->role_id == '7') {
                return redirect()->route('services_app.admin');
            }
            return redirect()->intended(route('admin'));
        }

        session()->flash('error', __('messages.wrong_password'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

     public function register()
    {
        $languages = ['ar', 'en', 'ur'];
        $language = app()->getLocale();
        $cities = StoreCity::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $categories = StoreCategory::select('id', "name_$language AS name")->where('displayed', 1)->get();
        $storeTerms = StoreSetting::select("store_terms_$language AS store_terms")->first();

        return view('auth.register', compact('languages', 'cities', 'categories', 'storeTerms'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function signUp(SignupRequest $request)
    {
        $storeService = new StoreService();

        $success = $storeService->create($request);

        if (!$success) {
            session()->flash('error', __('messages.something_went_wrong'));
            return redirect()->back();
        }

        Auth::attempt(['phone' => $request->phone, 'password' => $request->password]);
        return redirect()->route('store_app.admin');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function logout()
    {
        auth()->logout();
        return redirect(route('login'));
    }
}

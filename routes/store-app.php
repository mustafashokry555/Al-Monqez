<?php

use App\Http\Controllers\Admin\StoreApp\OrderController;
use App\Http\Controllers\Admin\StoreApp\CategoryController;
use App\Http\Controllers\Admin\StoreApp\CityController;
use App\Http\Controllers\Admin\StoreApp\ClassificationController;
use App\Http\Controllers\Admin\StoreApp\CouponController;
use App\Http\Controllers\Admin\StoreApp\DriverController;
use App\Http\Controllers\Admin\StoreApp\HomeController;
use App\Http\Controllers\Admin\StoreApp\ProductController;
use App\Http\Controllers\Admin\StoreApp\SettingController;
use App\Http\Controllers\Admin\StoreApp\SliderController;
use App\Http\Controllers\Admin\StoreApp\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth', 'access_store_app')->as('store_app.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin');

    Route::group(['middleware' => ['is_blocked'], 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'stores', 'as' => 'stores.'], function () {
            Route::group(['middleware' => 'has_ability:store_edit|store_delete'], function () {
                Route::get('/', [StoreController::class, 'index'])->name('index');
                Route::get('/{id}/reports', [StoreController::class, 'reports'])->name('reports');
            });

            Route::group(['middleware' => 'has_ability:store_create'], function () {
                Route::get('/create', [StoreController::class, 'create'])->name('create');
                Route::post('/store', [StoreController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:store_edit'], function () {
                Route::get('/edit/{id}', [StoreController::class, 'edit'])->name('edit');
                Route::put('/update', [StoreController::class, 'update'])->name('update');
                Route::put('/verify', [StoreController::class, 'verify'])->name('verify');
            });

            Route::group(['middleware' => 'has_ability:store_delete'], function () {
                Route::delete('/destroy', [StoreController::class, 'destroy'])->name('destroy');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'drivers', 'as' => 'drivers.'], function () {
            Route::group(['middleware' => 'has_ability:driver_edit|driver_delete'], function () {
                Route::get('/', [DriverController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:driver_create'], function () {
                Route::get('/create', [DriverController::class, 'create'])->name('create');
                Route::post('/store', [DriverController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:driver_edit'], function () {
                Route::get('/edit/{id}', [DriverController::class, 'edit'])->name('edit');
                Route::put('/update', [DriverController::class, 'update'])->name('update');
                Route::put('/verify', [DriverController::class, 'verify'])->name('verify');
            });

            Route::group(['middleware' => 'has_ability:driver_delete'], function () {
                Route::delete('/destroy', [DriverController::class, 'destroy'])->name('destroy');
                Route::delete('/image-destroy', [DriverController::class, 'destroyImage'])->name('image_destroy');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::group(['middleware' => 'has_ability:setting_change'], function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::post('/store', [SettingController::class, 'store'])->name('store');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'coupons', 'as' => 'coupons.'], function () {
            Route::group(['middleware' => 'has_ability:coupon_edit|coupon_delete'], function () {
                Route::get('/', [CouponController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:coupon_create'], function () {
                Route::get('/create', [CouponController::class, 'create'])->name('create');
                Route::post('/store', [CouponController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:coupon_edit'], function () {
                Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('edit');
                Route::put('/update', [CouponController::class, 'update'])->name('update');
            });

            Route::delete('/destroy', [CouponController::class, 'destroy'])->name('destroy')->middleware('has_ability:coupon_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::group(['middleware' => 'has_ability:store_order_control'], function () {
                Route::get('/', [OrderController::class, 'index'])->name('index');
                Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
                Route::put('/process', [OrderController::class, 'process'])->name('process');
                Route::delete('/destroy', [OrderController::class, 'destroy'])->name('destroy');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'classifications', 'as' => 'classifications.'], function () {
            Route::group(['middleware' => 'has_ability:classification_edit|classification_delete'], function () {
                Route::get('/', [ClassificationController::class, 'index'])->name('index');
                Route::get('/get_classifications', [ClassificationController::class, 'getClassificationsByStore'])->name('get_classifications');
            });

            Route::group(['middleware' => 'has_ability:classification_create'], function () {
                Route::get('/create', [ClassificationController::class, 'create'])->name('create');
                Route::post('/store', [ClassificationController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:classification_edit'], function () {
                Route::get('/edit/{id}', [ClassificationController::class, 'edit'])->name('edit');
                Route::put('/update', [ClassificationController::class, 'update'])->name('update');
                Route::put('/display', [ClassificationController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [ClassificationController::class, 'destroy'])->name('destroy')->middleware('has_ability:classification_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'patches', 'as' => 'patches.'], function () {
            Route::group(['middleware' => 'has_ability:patch_edit|patch_delete'], function () {
                Route::get('/', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:patch_create'], function () {
                Route::get('/create', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'create'])->name('create');
                Route::post('/store', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:patch_edit'], function () {
                Route::get('/edit/{id}', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'edit'])->name('edit');
                Route::put('/update', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'update'])->name('update');
                Route::put('/display', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [\App\Http\Controllers\Admin\StoreApp\ProductPatchController::class, 'destroy'])->name('destroy')->middleware('has_ability:patch_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::group(['middleware' => 'has_ability:product_edit|product_delete'], function () {
                Route::get('/', [ProductController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:product_create'], function () {
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/store', [ProductController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:product_edit'], function () {
                Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
                Route::put('/update', [ProductController::class, 'update'])->name('update');
                Route::put('/display', [ProductController::class, 'display'])->name('display');
            });

            Route::group(['middleware' => 'has_ability:product_delete'], function () {
                Route::delete('/destroy', [ProductController::class, 'destroy'])->name('destroy');
                Route::delete('/image-destroy', [ProductController::class, 'destroyImage'])->name('image_destroy');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
            Route::group(['middleware' => 'has_ability:category_edit|category_delete'], function () {
                Route::get('/', [CategoryController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:category_create'], function () {
                Route::get('/create', [CategoryController::class, 'create'])->name('create');
                Route::post('/store', [CategoryController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:category_edit'], function () {
                Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
                Route::put('/update', [CategoryController::class, 'update'])->name('update');
                Route::put('/display', [CategoryController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [CategoryController::class, 'destroy'])->name('destroy')->middleware('has_ability:category_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'cities', 'as' => 'cities.'], function () {
            Route::group(['middleware' => 'has_ability:city_edit|city_delete'], function () {
                Route::get('/', [CityController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:city_create'], function () {
                Route::get('/create', [CityController::class, 'create'])->name('create');
                Route::post('/store', [CityController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:city_edit'], function () {
                Route::get('/edit/{id}', [CityController::class, 'edit'])->name('edit');
                Route::put('/update', [CityController::class, 'update'])->name('update');
                Route::put('/display', [CityController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [CityController::class, 'destroy'])->name('destroy')->middleware('has_ability:city_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'sliders', 'as' => 'sliders.'], function () {
            Route::group(['middleware' => 'has_ability:slider_edit|slider_delete'], function () {
                Route::get('/', [SliderController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:slider_create'], function () {
                Route::get('/create', [SliderController::class, 'create'])->name('create');
                Route::post('/store', [SliderController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:slider_edit'], function () {
                Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('edit');
                Route::put('/update', [SliderController::class, 'update'])->name('update');
                Route::put('/display', [SliderController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [SliderController::class, 'destroy'])->name('destroy')->middleware('has_ability:slider_delete');
        });
    });
});

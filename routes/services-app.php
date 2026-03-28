<?php

use App\Http\Controllers\Admin\ServicesApp\CategoryController;
use App\Http\Controllers\Admin\ServicesApp\ChatController;
use App\Http\Controllers\Admin\ServicesApp\CityController;
use App\Http\Controllers\Admin\ServicesApp\CompanyController;
use App\Http\Controllers\Admin\ServicesApp\ComplaintController;
use App\Http\Controllers\Admin\ServicesApp\HomeController;
use App\Http\Controllers\Admin\ServicesApp\MapController;
use App\Http\Controllers\Admin\ServicesApp\NotificationController;
use App\Http\Controllers\Admin\ServicesApp\OfferController;
use App\Http\Controllers\Admin\ServicesApp\OrderController;
use App\Http\Controllers\Admin\ServicesApp\PartnerController;
use App\Http\Controllers\Admin\ServicesApp\RegionController;
use App\Http\Controllers\Admin\ServicesApp\ReportController;
use App\Http\Controllers\Admin\ServicesApp\ServiceController;
use App\Http\Controllers\Admin\ServicesApp\SettingController;
use App\Http\Controllers\Admin\ServicesApp\SliderController;
use App\Http\Controllers\Admin\ServicesApp\SubCategoryController;
use App\Http\Controllers\Admin\ServicesApp\WithdrawController;
use App\Http\Controllers\Admin\ServicesApp\WorkerController;
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

Route::middleware('auth', 'access_services_app')->as('services_app.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin');

    Route::group(['middleware' => ['is_blocked'], 'as' => 'admin.'], function () {
        Route::get('sub-categories/all', [SubCategoryController::class, 'all'])->name('sub.categories.all');

        Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {
            Route::group(['middleware' => 'has_ability:company_edit|company_delete'], function () {
                Route::get('/', [CompanyController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:company_create'], function () {
                Route::get('/create', [CompanyController::class, 'create'])->name('create');
                Route::post('/store', [CompanyController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:company_edit'], function () {
                Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('edit');
                Route::put('/update', [CompanyController::class, 'update'])->name('update');
                Route::put('/verify', [CompanyController::class, 'verify'])->name('verify');
            });

            Route::group(['middleware' => 'has_ability:company_delete'], function () {
                Route::delete('/destroy', [CompanyController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'workers', 'as' => 'workers.'], function () {
            Route::group(['middleware' => 'has_ability:worker_edit|worker_delete'], function () {
                Route::get('/', [WorkerController::class, 'index'])->name('index');
                Route::get('/{id}/evaluations', [WorkerController::class, 'evaluations'])->name('evaluations');
            });

            Route::group(['prefix' => 'joining-requests', 'as' => 'joining_requests.', 'middleware' => ['has_ability:worker_edit', 'is_admin']], function () {
                Route::get('/', [WorkerController::class, 'joiningRequests'])->name('index');
                Route::put('/accept', [WorkerController::class, 'acceptJoiningRequest'])->name('accept');
            });

            Route::group(['middleware' => 'has_ability:worker_create'], function () {
                Route::get('/create', [WorkerController::class, 'create'])->name('create');
                Route::post('/store', [WorkerController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:worker_edit'], function () {
                Route::get('/edit/{id}', [WorkerController::class, 'edit'])->name('edit');
                Route::put('/update', [WorkerController::class, 'update'])->name('update');
                Route::put('/verify', [WorkerController::class, 'verify'])->name('verify');
            });

            Route::group(['middleware' => 'has_ability:worker_delete'], function () {
                Route::delete('/destroy', [WorkerController::class, 'destroy'])->name('destroy');
                Route::delete('/image-destroy', [WorkerController::class, 'destroyImage'])->name('image_destroy');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::group(['middleware' => 'has_ability:setting_change'], function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::post('/store', [SettingController::class, 'store'])->name('store');
                Route::group(['prefix' => 'regions', 'as' => 'regions.'], function () {
                    Route::get('/edit', [RegionController::class, 'edit'])->name('edit');
                    Route::put('/update', [RegionController::class, 'update'])->name('update');
                });
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
            Route::group(['middleware' => 'has_ability:notification_control'], function () {
                Route::get('/received', [NotificationController::class, 'received'])->name('received');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'maps', 'as' => 'maps.', 'middleware' => 'has_ability:control_panel_control'], function () {
            Route::get('/', [MapController::class, 'index'])->name('index');
            Route::get('/locations', [MapController::class, 'locations'])->name('locations');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'chats', 'as' => 'chats.', 'middleware' => 'has_ability:chat_control'], function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('/show/{id}', [ChatController::class, 'show'])->name('show');
            Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message');
            Route::get('/check-new-messages', [ChatController::class, 'checkNewMessage'])->name('check-new-messages');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => 'has_ability:report_control'], function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'partners', 'as' => 'partners.'], function () {
            Route::group(['middleware' => 'has_ability:partner_edit|partner_delete'], function () {
                Route::get('/', [PartnerController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:partner_create'], function () {
                Route::get('/create', [PartnerController::class, 'create'])->name('create');
                Route::post('/store', [PartnerController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:partner_edit'], function () {
                Route::get('/edit/{id}', [PartnerController::class, 'edit'])->name('edit');
                Route::put('/update', [PartnerController::class, 'update'])->name('update');
            });

            Route::delete('/destroy', [PartnerController::class, 'destroy'])->name('destroy')->middleware('has_ability:partner_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::group(['middleware' => 'has_ability:order_control'], function () {
                Route::get('/', [OrderController::class, 'index'])->name('index');
                Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
                Route::put('/assign-worker', [OrderController::class, 'assignWorker'])->name('assign.worker');
                Route::delete('/destroy', [OrderController::class, 'destroy'])->name('destroy')->middleware('is_admin');
                Route::group(['prefix' => 'complaints', 'as' => 'complaints.', 'middleware' => 'is_admin'], function () {
                    Route::get('/', [ComplaintController::class, 'index'])->name('index');
                    Route::put('/process', [ComplaintController::class, 'process'])->name('process');
                });
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'offers', 'as' => 'offers.'], function () {
            Route::group(['middleware' => 'is_company'], function () {
                Route::get('/', [OfferController::class, 'index'])->name('index');
                Route::put('/send', [OfferController::class, 'make'])->name('send');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'withdraws', 'as' => 'withdraws.'], function () {
            Route::group(['middleware' => 'has_ability:withdraw_control'], function () {
                Route::get('/', [WithdrawController::class, 'index'])->name('index');
                Route::put('/process', [WithdrawController::class, 'process'])->name('process');
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

        Route::group(['prefix' => 'sub-categories', 'as' => 'sub.categories.'], function () {
            Route::group(['middleware' => 'has_ability:sub_category_edit|sub_category_delete'], function () {
                Route::get('/', [SubCategoryController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:sub_category_create'], function () {
                Route::get('/create', [SubCategoryController::class, 'create'])->name('create');
                Route::post('/store', [SubCategoryController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:sub_category_edit'], function () {
                Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('edit');
                Route::put('/update', [SubCategoryController::class, 'update'])->name('update');
                Route::put('/display', [SubCategoryController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [SubCategoryController::class, 'destroy'])->name('destroy')->middleware('has_ability:sub_category_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
            Route::group(['middleware' => 'has_ability:service_edit|service_delete'], function () {
                Route::get('/', [ServiceController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:service_create'], function () {
                Route::get('/create', [ServiceController::class, 'create'])->name('create');
                Route::post('/store', [ServiceController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:service_edit'], function () {
                Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('edit');
                Route::put('/update', [ServiceController::class, 'update'])->name('update');
                Route::put('/display', [ServiceController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [ServiceController::class, 'destroy'])->name('destroy')->middleware('has_ability:service_delete');
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

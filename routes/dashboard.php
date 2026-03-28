<?php

use App\Http\Controllers\Admin\Dashboard\AboutController;
use App\Http\Controllers\Admin\Dashboard\AdminController;
use App\Http\Controllers\Admin\Dashboard\AuthController;
use App\Http\Controllers\Admin\Dashboard\ClientController;
use App\Http\Controllers\Admin\Dashboard\ContactController;
use App\Http\Controllers\Admin\Dashboard\HomeController;
use App\Http\Controllers\Admin\Dashboard\NotificationController;
use App\Http\Controllers\Admin\Dashboard\ProfileController;
use App\Http\Controllers\Admin\Dashboard\SettingController;
use App\Http\Controllers\Admin\Dashboard\SocialController;
use App\Http\Controllers\Admin\Dashboard\TermController;
use App\Http\Controllers\Admin\Dashboard\UserController;
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

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'signIn'])->name('signIn');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'signUp'])->name('signUp');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin')->middleware('is_admin');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::group(['middleware' => ['is_admin', 'is_blocked'], 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'admins', 'as' => 'admins.'], function () {
            Route::group(['middleware' => 'has_ability:admin_edit|admin_delete'], function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:admin_create'], function () {
                Route::get('/create', [AdminController::class, 'create'])->name('create');
                Route::post('/store', [AdminController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:admin_edit'], function () {
                Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
                Route::put('/update', [AdminController::class, 'update'])->name('update');
                Route::put('/verify', [AdminController::class, 'verify'])->name('verify');
            });

            Route::delete('/destroy', [AdminController::class, 'destroy'])->name('destroy')->middleware('has_ability:admin_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'clients', 'as' => 'clients.'], function () {
            Route::group(['middleware' => 'has_ability:client_edit|client_delete'], function () {
                Route::get('/', [ClientController::class, 'index'])->name('index');
                Route::get('/{id}/reports', [ClientController::class, 'reports'])->name('reports');
            });

            Route::group(['middleware' => 'has_ability:client_create'], function () {
                Route::get('/create', [ClientController::class, 'create'])->name('create');
                Route::post('/store', [ClientController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:client_edit'], function () {
                Route::get('/edit/{id}', [ClientController::class, 'edit'])->name('edit');
                Route::put('/update', [ClientController::class, 'update'])->name('update');
                Route::put('/verify', [ClientController::class, 'verify'])->name('verify');
            });

            Route::group(['middleware' => 'has_ability:client_delete'], function () {
                Route::delete('/destroy', [ClientController::class, 'destroy'])->name('destroy');
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

        Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
            Route::group(['middleware' => 'has_ability:notification_control'], function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::post('/store', [NotificationController::class, 'store'])->name('store');
            });
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'users', 'as' => 'users.', 'middleware' => 'has_ability:users_activity_log_control'], function () {
            Route::get('/activity-logs', [UserController::class, 'activityLogs'])->name('activity_logs');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'socials', 'as' => 'socials.'], function () {
            Route::group(['middleware' => 'has_ability:social_edit|social_delete'], function () {
                Route::get('/', [SocialController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:social_create'], function () {
                Route::get('/create', [SocialController::class, 'create'])->name('create');
                Route::post('/store', [SocialController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:social_edit'], function () {
                Route::get('/edit/{id}', [SocialController::class, 'edit'])->name('edit');
                Route::put('/update', [SocialController::class, 'update'])->name('update');
                Route::put('/display', [SocialController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [SocialController::class, 'destroy'])->name('destroy')->middleware('has_ability:social_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'terms', 'as' => 'terms.'], function () {
            Route::group(['middleware' => 'has_ability:term_edit|term_delete'], function () {
                Route::get('/', [TermController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:term_create'], function () {
                Route::get('/create', [TermController::class, 'create'])->name('create');
                Route::post('/store', [TermController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:term_edit'], function () {
                Route::get('/edit/{id}', [TermController::class, 'edit'])->name('edit');
                Route::put('/update', [TermController::class, 'update'])->name('update');
                Route::put('/display', [TermController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [TermController::class, 'destroy'])->name('destroy')->middleware('has_ability:term_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'abouts', 'as' => 'abouts.'], function () {
            Route::group(['middleware' => 'has_ability:about_edit|about_delete'], function () {
                Route::get('/', [AboutController::class, 'index'])->name('index');
            });

            Route::group(['middleware' => 'has_ability:about_create'], function () {
                Route::get('/create', [AboutController::class, 'create'])->name('create');
                Route::post('/store', [AboutController::class, 'store'])->name('store');
            });

            Route::group(['middleware' => 'has_ability:about_edit'], function () {
                Route::get('/edit/{id}', [AboutController::class, 'edit'])->name('edit');
                Route::put('/update', [AboutController::class, 'update'])->name('update');
                Route::put('/display', [AboutController::class, 'display'])->name('display');
            });

            Route::delete('/destroy', [AboutController::class, 'destroy'])->name('destroy')->middleware('has_ability:about_delete');
        });

        /*---------------------------------------------------------------------------------------------------------------*/

        Route::group(['prefix' => 'contacts', 'as' => 'contacts.', 'middleware' => 'has_ability:contact_control'], function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/show/{id}', [ContactController::class, 'show'])->name('show');
            Route::delete('/destroy', [ContactController::class, 'destroy'])->name('destroy');
        });
    });
});

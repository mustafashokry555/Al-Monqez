<?php

use App\Http\Controllers\Api\StoreApp\CartController;
use App\Http\Controllers\Api\StoreApp\CategoryController;
use App\Http\Controllers\Api\StoreApp\CouponController;
use App\Http\Controllers\Api\StoreApp\FavoriteController;
use App\Http\Controllers\Api\StoreApp\MainController;
use App\Http\Controllers\Api\StoreApp\OrderController;
use App\Http\Controllers\Api\StoreApp\ProductController;
use App\Http\Controllers\Api\StoreApp\StoreController;
use App\Http\Controllers\Api\Main\TamaraController;
use App\Http\Controllers\Api\Main\TabbyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('tabby')->group(function () {
    
    // === ????? ??????? (Redirects) ===
    // ??? ??????? ???????? ??????? (Redirect) ??? ??? ?? ???? GET
    Route::get('success', [TabbyController::class, 'success'])->name('tabby.success');
    Route::get('cancel', [TabbyController::class, 'cancel'])->name('tabby.cancel');
    Route::get('failure', [TabbyController::class, 'failure'])->name('tabby.failure');

    // === ????? ??? API ???????? (??????? ???) ===
    // ??? ??? POST ???? ??? ?? ???????? ??????? ?? ??? Webhook
    Route::post('{paymentId}/capture', [TabbyController::class, 'capture']);
    Route::post('{paymentId}/refund', [TabbyController::class, 'refund']);
});

// Webhooks (???? ???? ??? prefix ??? ??? ?????? ???????)
Route::post('/webhooks/tabby', [TabbyController::class, 'handleWebhook']);
Route::post('/webhooks/tabbyLive', [TabbyController::class, 'handleWebhook']);



Route::get('/tamara/success', [TamaraController::class, 'success']);
Route::get('/tamara/failure', [TamaraController::class, 'failure']);
Route::get('/tamara/cancel', [TamaraController::class, 'cancel']);
Route::post('/tamara/webhook', [TamaraController::class, 'handle']); // 

Route::middleware('change_language')->group(function () {
    if (request()->header('Authorization')) {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/home-data', [MainController::class, 'homeData']);
            Route::get('/driver-terms', [MainController::class, 'driverTerms']);

            Route::group(['prefix' => '/products'], function () {
                Route::get('/', [ProductController::class, 'index']);
                Route::get('/show/{id}', [ProductController::class, 'show'])->name('api.store-app.product.show');
            });

            Route::group(['prefix' => '/stores'], function () {
                Route::get('/show/{id}', [StoreController::class, 'show'])->name('api.store-app.store.show');
            });
        });
    } else {
        Route::get('/home-data', [MainController::class, 'homeData']);
            Route::get('/driver-terms', [MainController::class, 'driverTerms']);

        Route::group(['prefix' => '/products'], function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/show/{id}', [ProductController::class, 'show'])->name('api.store-app.product.show');
        });

        Route::group(['prefix' => '/stores'], function () {
            Route::get('/show/{id}', [StoreController::class, 'show'])->name('api.store-app.store.show');
        });
    }

    Route::group(['prefix' => '/categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
    });

    Route::group(['prefix' => '/stores'], function () {
        Route::get('/{category_id}', [StoreController::class, 'index']);
    });

    Route::middleware('auth:sanctum', 'is_client')->group(function () {
        Route::group(['prefix' => '/favorites'], function () {
            Route::group(['prefix' => '/products'], function () {
                Route::get('/', [FavoriteController::class, 'favoriteProducts']);
                Route::post('/toggle', [FavoriteController::class, 'toggleFavoriteProduct']);
            });
            Route::group(['prefix' => '/stores'], function () {
                Route::get('/', [FavoriteController::class, 'favoriteStores'])->name('api.store-app.stores.favorites');
                Route::post('/toggle', [FavoriteController::class, 'toggleFavoriteStore']);
            });
        });

        Route::group(['prefix' => '/cart'], function () {
            Route::get('/', [CartController::class, 'index']);
            Route::get('/show/{store_id}', [CartController::class, 'show'])->name('api.store-app.cart.show');
            Route::post('/add', [CartController::class, 'addToCart']);
            Route::post('/remove', [CartController::class, 'removeFromCart']);
            Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
        });

        Route::group(['prefix' => '/orders'], function () {
            Route::post('/make', [OrderController::class, 'make']);
            Route::post('/evaluate', [OrderController::class, 'evaluate']);
            Route::post('/cancel', [OrderController::class, 'cancel']);
        });
    
     // 👇 الروابط الجديدة
        Route::post('/tabby-payment/{order_id}', [OrderController::class, 'tabbyPayment']);
        Route::post('/tamara-payment/{order_id}', [OrderController::class, 'tamaraPayment']);
    
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => '/orders'], function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/show/{id}', [OrderController::class, 'show'])->name('api.store-app.order.show');
        });
    });

    Route::middleware('auth:sanctum', 'is_driver')->group(function () {
        Route::group(['prefix' => '/orders'], function () {
            Route::post('/process', [OrderController::class, 'process']);
            Route::post('/notify', [OrderController::class, 'notify']);
        });
    });
});

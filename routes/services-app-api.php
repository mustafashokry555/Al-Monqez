<?php

use App\Http\Controllers\Api\ServicesApp\ChatController;
use App\Http\Controllers\Api\ServicesApp\ComplaintController;
use App\Http\Controllers\Api\ServicesApp\LocationController;
use App\Http\Controllers\Api\ServicesApp\MainController;
use App\Http\Controllers\Api\ServicesApp\OfferController;
use App\Http\Controllers\Api\ServicesApp\OrderController;
use App\Http\Controllers\Api\ServicesApp\ProfileController;
use App\Http\Controllers\Api\ServicesApp\ServiceController;
use App\Http\Controllers\Api\ServicesApp\WithdrawController;
use App\Http\Controllers\Api\ServicesApp\TamaraController;
use App\Http\Controllers\Api\ServicesApp\TabbyController;

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


Route::prefix('tabbyoffer')->group(function () {
    
    // === روابط التوجيه (Redirects) ===
    // هذه الروابط يستدعيها المتصفح (Redirect) لذا يجب أن تكون GET
    Route::get('success', [TabbyController::class, 'success'])->name('tabbyoffer.success');
    Route::get('cancel', [TabbyController::class, 'cancel'])->name('tabbyoffer.cancel');
    Route::get('failure', [TabbyController::class, 'failure'])->name('tabbyoffer.failure');

    // === روابط الـ API الداخلية (للسيرفر فقط) ===
    // هذه تظل POST لأنك أنت من تستدعيها داخلياً أو عبر Webhook
    Route::post('{paymentId}/capture', [TabbyController::class, 'capture']);
    Route::post('{paymentId}/refund', [TabbyController::class, 'refund']);
// Webhooks (تبقى خارج الـ prefix إذا كان الرابط مختلفاً)
Route::post('/webhooks/tabby', [TabbyController::class, 'handleWebhook']);
Route::post('/webhooks/tabbyLive', [TabbyController::class, 'handleWebhook']);
});





Route::get('/tamaraoffer/success', [TamaraController::class, 'success']);
Route::get('/tamaraoffer/failure', [TamaraController::class, 'failure']);
Route::get('/tamaraoffer/cancel', [TamaraController::class, 'cancel']);
Route::post('/tamaraoffer/webhook', [TamaraController::class, 'handle']); // 




Route::middleware('change_language')->group(function () {
    if (request()->header('Authorization')) {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/home-data', [MainController::class, 'homeData']);
        });
    } else {
        Route::get('/home-data', [MainController::class, 'homeData']);
    }
    Route::get('/cities', [MainController::class, 'cities']);
    Route::get('/cities-and-categories', [MainController::class, 'citiesAndCategories']);
    Route::get('/sub-categories', [MainController::class, 'subCategories'])->name('api.sub.categories');
    Route::get('/sub-categories-with-services', [MainController::class, 'subCategoriesWithServices'])->name('api.sub.categories.with.services');

    Route::group(['prefix' => '/services', 'as' => 'api.services.'], function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/search', [ServiceController::class, 'search'])->name('search');
        Route::get('/common', [ServiceController::class, 'common'])->name('common');
    });

    Route::middleware('auth:sanctum', 'is_verified')->group(function () {
        Route::group(['prefix' => '/orders', 'as' => 'orders.'], function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
            Route::post('/cancel', [OrderController::class, 'cancel']);
            Route::get('/statuses', [OrderController::class, 'statuses']);
        });

        Route::middleware('is_blocked')->group(function () {
            Route::group(['prefix' => '/chats'], function () {
                Route::get('/show/{id?}', [ChatController::class, 'show']);
                Route::post('/send-message', [ChatController::class, 'sendMessage']);
            });

            Route::middleware('is_client')->group(function () {
                Route::group(['prefix' => '/locations'], function () {
                    Route::get('/', [LocationController::class, 'index']);
                    Route::post('/add', [LocationController::class, 'add']);
                    Route::post('/delete', [LocationController::class, 'delete']);
                });

                Route::group(['prefix' => '/orders'], function () {
                    Route::get('/available-dates', [OrderController::class, 'availableDates']);
                    Route::post('/make', [OrderController::class, 'make']);
                    Route::get('/{id}/offers', [OfferController::class, 'index']);
                    Route::post('/evaluate', [OrderController::class, 'evaluate']);
                    Route::get('/complaints', [ComplaintController::class, 'index']);
                    Route::post('/complain', [ComplaintController::class, 'make']);
                });

                Route::group(['prefix' => '/offers'], function () {
                    Route::post('/process', [OfferController::class, 'process']);
                    Route::post('/apply-coupon', [OfferController::class, 'applyCoupon']);


                Route::post('/tabby-payment-offer/{offer_id?}', [OfferController::class, 'tabbyPaymentOffer']);
    Route::post('/tamara-payment-offer/{offer_id?}', [OfferController::class, 'tamaraPaymentOffer']);
            
                });
            
            
             
            });

            Route::middleware('is_worker', 'is_accepted')->group(function () {
                Route::post('/users/update-location', [LocationController::class, 'updateUserLocation']);
                Route::get('/balance-with-statistics', [WithdrawController::class, 'balanceWithStatistics']);
                Route::post('/withdraws/make', [WithdrawController::class, 'make']);

                Route::group(['prefix' => '/offers'], function () {
                    Route::post('/make', [OfferController::class, 'make']);
                     
                });

                Route::group(['prefix' => '/orders'], function () {
                    Route::post('/process', [OrderController::class, 'process']);
                    Route::get('/evaluations', [ProfileController::class, 'evaluations']);
                });
            });
        });
    
   
    
    
    });
});

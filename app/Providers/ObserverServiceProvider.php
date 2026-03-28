<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Evaluation;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\Setting;
use App\Models\StoreClassification;
use App\Models\StoreEvaluation;
use App\Models\StoreOrder;
use App\Models\StoreProduct;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Withdraw;
use App\Observers\Dashboard\SettingObserver;
use App\Observers\Dashboard\UserObserver;
use App\Observers\ServicesApp\CategoryObserver;
use App\Observers\ServicesApp\ComplaintObserver;
use App\Observers\ServicesApp\EvaluationObserver;
use App\Observers\ServicesApp\OfferObserver;
use App\Observers\ServicesApp\OrderObserver;
use App\Observers\ServicesApp\SubCategoryObserver;
use App\Observers\ServicesApp\WithdrawObserver;
use App\Observers\StoreApp\ClassificationObserver;
use App\Observers\StoreApp\EvaluationObserver as StoreEvaluationObserver;
use App\Observers\StoreApp\OrderObserver AS StoreOrderObserver;
use App\Observers\StoreApp\ProductObserver;
use App\Observers\StoreApp\StoreObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Setting::observe(SettingObserver::class);
        Category::observe(CategoryObserver::class);
        SubCategory::observe(SubCategoryObserver::class);
        Order::observe(OrderObserver::class);
        OrderRequest::observe(OfferObserver::class);
        Evaluation::observe(EvaluationObserver::class);
        Withdraw::observe(WithdrawObserver::class);
        User::observe(UserObserver::class);
        User::observe(StoreObserver::class);
        StoreClassification::observe(ClassificationObserver::class);
        StoreProduct::observe(ProductObserver::class);
        StoreOrder::observe(StoreOrderObserver::class);
        StoreEvaluation::observe(StoreEvaluationObserver::class);
        Complaint::observe(ComplaintObserver::class);
    }
}

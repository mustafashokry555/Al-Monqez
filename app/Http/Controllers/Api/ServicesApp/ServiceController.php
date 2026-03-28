<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\ServicesApp\ServiceResource;
use App\Http\Resources\ServicesApp\SubCategoryResource;
use App\Models\Order;
use App\Models\Service;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $language = app()->getLocale();
        $subCategory = SubCategory::query()->select(
            'sub_categories.id',
            'sub_categories.location_type',
            "sub_categories.name_$language AS name"
        );

        if (!$request->filled('order_id')) {
            $subCategory->with([
                'services' => function ($query) use ($language) {
                    $query->select(
                        'services.id',
                        'services.sub_category_id',
                        "services.name_$language AS name",
                        "services.brief_$language AS brief",
                        "services.description_$language AS description",
                        'services.image'
                    )
                        ->where([['displayed', '1']])
                        ->orderBy('name', 'ASC');
                }
            ]);
        } else {
            $order = Order::select('worker_id', 'sub_categories.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'orders.sub_category_id')
                ->where('orders.id', $request->order_id)
                ->firstOrFail();

            if ($order) {
                $subCategory->with([
                    'services' => function ($query) use ($language, $order) {
                        $query->select(
                            'services.id',
                            'services.sub_category_id',
                            "services.name_$language AS name",
                            "services.brief_$language AS brief",
                            "services.description_$language AS description",
                            'services.image'
                        )
                            ->join('user_services', 'user_services.service_id', '=', 'services.id')
                            ->where([['displayed', '1']])
                            ->where('user_services.user_id', $order->worker_id)
                            ->orderBy('name', 'ASC');
                    }
                ])
                    ->join('user_sub_categories', 'user_sub_categories.sub_category_id', '=', 'sub_categories.id')
                    ->where('sub_categories.category_id', $order->category_id)
                    ->where('user_sub_categories.user_id', $order->worker_id);
            }
        }

        $subCategory = $subCategory->findOrFail($request->sub_category_id);

        return $this->apiResponse(200, 'sub category with services', null, [
            'sub_category' => new SubCategoryResource($subCategory)
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function search(Request $request)
    {
        $language = app()->getLocale();
        $perPage = $request->query('per_page', 10);
        $searchTerm = $request->query('search', '');

        $services = Service::select(
            'services.id',
            'services.sub_category_id',
            "services.name_$language AS name",
            "services.brief_$language AS brief",
            DB::raw("(SUM(evaluations.rating) / COUNT(CASE WHEN evaluations.rating > 0 THEN services.id END)) AS rating"),
            'services.image'
        )
            ->leftJoin('order_services', 'services.id', '=', 'order_services.service_id')
            ->leftJoin('evaluations', 'order_services.order_id', '=', 'evaluations.order_id')
            ->where([['services.displayed', '1']])
            ->where(function ($query) use ($searchTerm, $language) {
                $query->where("services.name_$language", 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere("services.brief_$language", 'LIKE', '%' . $searchTerm . '%');
            })
            ->orderBy('rating', 'DESC')
            ->groupBy('services.id', 'services.sub_category_id', "services.name_$language", "services.brief_$language", 'services.image')
            ->paginate($perPage);

        return $this->apiResponse(200, 'search results for services', null, [
            'services' => ServiceResource::collection($services),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'next_page_url' => $services->nextPageUrl(),
                'prev_page_url' => $services->previousPageUrl()
            ]
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function common(Request $request)
    {
        $language = app()->getLocale();
        $perPage = $request->query('per_page', 10);

        $services = Service::select(
            'services.id',
            'services.sub_category_id',
            "services.name_$language AS name",
            "services.brief_$language AS brief",
            DB::raw("(SUM(evaluations.rating) / COUNT(CASE WHEN evaluations.rating > 0 THEN services.id END)) AS rating"),
            'services.image'
        )
            ->leftJoin('order_services', 'services.id', '=', 'order_services.service_id')
            ->leftJoin('evaluations', 'order_services.order_id', '=', 'evaluations.order_id')
            ->where([['services.displayed', '1']])
            ->orderBy('rating', 'DESC')
            ->groupBy('services.id', 'services.sub_category_id', "services.name_$language", "services.brief_$language", 'services.image')
            ->paginate($perPage);

        return $this->apiResponse(200, 'services', null, [
            'services' => ServiceResource::collection($services),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'per_page' => $services->perPage(),
                'total' => $services->total(),
                'next_page_url' => $services->nextPageUrl(),
                'prev_page_url' => $services->previousPageUrl()
            ]
        ]);
    }
}

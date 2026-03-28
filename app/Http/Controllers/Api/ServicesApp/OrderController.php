<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Api\ServicesApp\Orders\CancelOrderRequest;
use App\Http\Requests\Api\ServicesApp\Orders\EvaluateOrderRequest;
use App\Http\Requests\Api\ServicesApp\Orders\MakeOrderRequest;
use App\Http\Requests\Api\ServicesApp\Orders\ProcessOrderRequest;
use App\Http\Resources\ServicesApp\OrderResource;
use App\Http\Resources\ServicesApp\OfferResource;

use App\Models\OrderRequest;

use App\Models\Chat;
use App\Models\Evaluation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\OrderLocation;
use App\Models\OrderService;
use App\Models\OrderSetting;
use App\Services\RegionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponse, FileStorage;

    public function availableDates()
    {
        $orderSetting = OrderSetting::select('start_time', 'end_time')->first();
        $dates = [];

        if ($orderSetting) {
            $today = date('Y-m-d');
            $date = $today;
            $time = date('H:00:00', strtotime(date('H:i:s') . '+1 hour'));
            if ($time > $orderSetting->end_time) {
                $date = date('Y-m-d', strtotime($date . '+1 day'));
                $time = $orderSetting->start_time;
            } else if ($time < $orderSetting->start_time) {
                $time = $orderSetting->start_time;
            }

            for ($i = 0; $i < 7; $i++) {
                $details = [];
                $times = [];
                $details['day'] = ($date == $today) ? __('admin.today') : Carbon::parse($date)->locale(app()->getLocale())->translatedFormat('l');
                $details['text'] = Carbon::parse($date)->locale(app()->getLocale())->translatedFormat('j F');
                $details['date'] = $date;

                while ($time <= $orderSetting->end_time) {
                    array_push($times, [
                        'text' => Carbon::parse($time)->locale(app()->getLocale())->translatedFormat('h:i A'),
                        'time' => $time,
                    ]);

                    if ($time == '23:00:00') break;

                    $time = date('H:00:00', strtotime($time . '+1 hour'));
                }

                $details['times'] = $times;
                array_push($dates, $details);

                $date = date('Y-m-d', strtotime($date . '+1 day'));
                $time = $orderSetting->start_time;
            }
        }

        return $this->apiResponse(200, 'dates', null, $dates);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function index(Request $request)
    {
        $language = app()->getLocale();
        $status = $request->query('status', 0);
        $user = auth()->user();

        $orders = Order::query()
            ->select(
                'orders.id',
                'orders.parent_order_id',
                "categories.name_$language AS category_name",
                "sub_categories.name_$language AS sub_category_name",
                'orders.total',
                'orders.date',
                'orders.time',
                'orders.status',
                'orders.warranty_end_date'
            )
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->addSelect([
                'have_new_notifications' => DB::raw("
                EXISTS (
                    SELECT 1 FROM notifications
                    WHERE notifications.notifiable_id = orders.id
                    AND notifications.notifiable_type = 'order'
                    AND notifications.user_id = {$user->id}
                    AND notifications.read = 0
                ) AS have_new_notifications
            "),
                'have_new_messages' => DB::raw("
                (
                    SELECT COUNT(*)
                    FROM chats
                    JOIN messages ON messages.chat_id = chats.id
                    WHERE chats.order_id = orders.id
                    AND messages.read = 0
                    AND messages.user_id != {$user->id}
                ) AS have_new_messages
            "),
            ]);

        if ($user->role_id == '3') {
            $orders->addSelect('users.name AS client_name')
                ->join('users', 'orders.client_id', '=', 'users.id');

            if ($status == '0') {
                $orders->addSelect('order_requests.price AS total')
                    ->join('order_requests', 'orders.id', '=', 'order_requests.order_id')
                    ->where('order_requests.worker_id', $user->id);
            } else {
                $orders->where('orders.worker_id', $user->id);
            }
        } else {
            $orders->addSelect(
                DB::raw('(CASE WHEN orders.status <= 1 && orders.company_id IS NOT NULL && orders.worker_id IS NULL THEN companies.name ELSE workers.name END) AS worker_name'),
                DB::raw('(CASE WHEN orders.status <= 1 && orders.company_id IS NOT NULL && orders.worker_id IS NULL THEN companies.phone ELSE workers.phone END) AS worker_phone')
            )
                ->leftJoin('users AS workers', 'orders.worker_id', '=', 'workers.id')
                ->leftJoin('users AS companies', 'orders.company_id', '=', 'companies.id')
                ->where('orders.client_id', $user->id);

            if ($status == '0') {
                $orders->withCount(['requests' => function ($query) {
                    $query->whereNotNull('order_requests.price');
                }]);
            }
        }

        $orders = $orders->where('orders.status', $status)
            ->orderBy('orders.created_at', 'DESC')
            ->get();

        return $this->apiResponse(200, 'orders', null, OrderResource::collection($orders));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $order = Order::query()
            ->select(
                'orders.id',
                'orders.parent_order_id',
                "categories.name_$language AS category_name",
                "sub_categories.name_$language AS sub_category_name",
                "cities.name_$language AS city_name",
                'orders.description',
                'orders.date',
                'orders.time',
                'orders.status',
        'orders.payment_method',
        'orders.payment',
                'start_locations.title AS start_title',
                'start_locations.latitude AS start_latitude',
                'start_locations.longitude AS start_longitude',
                'end_locations.title AS end_title',
                'end_locations.latitude AS end_latitude',
                'end_locations.longitude AS end_longitude',
                'orders.warranty_end_date'
            )
            ->with([
            'childOrders' => function ($query) {
                    $query->select('orders.id', 'orders.parent_order_id');
                },
                'requests',
                'services' => function ($query) use ($language) {
                    $query->select('services.id', 'order_services.order_id', DB::raw("GROUP_CONCAT(services.name_$language SEPARATOR ' - ') AS description"))
                        ->join('services', 'order_services.service_id', '=', 'services.id')
                        ->groupBy('services.id', 'order_services.order_id');
                },
                'problemImages' => function ($query) {
                    $query->select('order_id', 'image');
                }
            ])
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->join('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('order_locations AS start_locations', function ($join) {
                $join->on('start_locations.order_id', '=', 'orders.id')
                    ->where('start_locations.type', '0');
            })
            ->join('order_locations AS end_locations', function ($join) {
                $join->on('end_locations.order_id', '=', 'orders.id')
                    ->where('end_locations.type', '1');
            });

        if (auth()->user()->role_id == '3') {
            $order->addSelect(DB::raw('(CASE WHEN orders.status = 0 THEN order_requests.price ELSE orders.total END) AS total'), 'users.name AS client_name', 'users.image AS client_image')
                ->join('users', 'orders.client_id', '=', 'users.id')
                ->leftJoin('order_requests', 'orders.id', '=', 'order_requests.order_id')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('order_requests.worker_id', auth()->id())
                            ->where('orders.status', '0');
                    })
                        ->orWhere('orders.worker_id', auth()->id());
                });
        } else {
            $order->addSelect(
                'orders.total',
                DB::raw('(CASE WHEN orders.status <= 1 && orders.company_id IS NOT NULL && orders.worker_id IS NULL THEN companies.name ELSE workers.name END) AS worker_name'),
                DB::raw('(CASE WHEN orders.status <= 1 && orders.company_id IS NOT NULL && orders.worker_id IS NULL THEN companies.phone ELSE workers.phone END) AS worker_phone'),
                DB::raw('(CASE WHEN orders.status <= 1 && orders.company_id IS NOT NULL && orders.worker_id IS NULL THEN companies.image ELSE workers.image END) AS worker_image')
            )
                ->withCount(['requests' => function ($query) {
                    $query->whereNotNull('order_requests.price');
                }])
                ->leftJoin('users AS workers', 'orders.worker_id', '=', 'workers.id')
                ->leftJoin('users AS companies', 'orders.company_id', '=', 'companies.id')
                ->where('orders.client_id', auth()->id());
        }

        $order = $order->findOrFail($id);
    
    /*
    // جلب العروض مباشرة بدون Resource
    $offers = OrderRequest::select(
            'order_requests.id',
            'order_requests.order_id',
            'order_requests.price',
            'orders.deposit_ratio',
            'orders.vat',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
            'orders.date',
            'orders.time',
            'users.name AS worker_name',
            'users.phone AS worker_phone',
            'users.image AS worker_image'
        )
        ->join('orders', 'order_requests.order_id', '=', 'orders.id')
        ->join('users', 'order_requests.worker_id', '=', 'users.id')
        ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
        ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
        ->where([['orders.id', $id], ['orders.status', '0'], ['orders.client_id', auth()->id()]])
        ->whereNotNull('order_requests.price')
        ->get();
    */
    

        Notification::where([['notifiable_type', 'order'], ['notifiable_id', $id], ['user_id', auth()->id()], ['read', 0]])->update(['read' => 1]);

    
    return $this->apiResponse(
    200,
    'order',
    null,
    [
        'order' => new OrderResource($order),
       // 'offers' => OfferResource::collection($offers)
    ]
);
    
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function make(MakeOrderRequest $request)
    {
        $start_in_region = true;
        $end_in_region = true;
        if ($request->filled('start_latitude') && $request->filled('start_longitude')) {
            $start_in_region = RegionService::detect(
                $request->start_latitude,
                $request->start_longitude
            );
        }
        if ($request->filled('end_latitude') && $request->filled('end_longitude')) {
            $end_in_region = RegionService::detect(
                $request->end_latitude,
                $request->end_longitude
            );
        }

        if (!$start_in_region || !$end_in_region) {
            return $this->apiResponse(400, __('messages.order_not_in_region'));
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'parent_order_id' => $request->order_id ?? null,
                'client_id' => auth()->id(),
                'city_id' => $request->parent_order?->city_id ?? $request->city_id,
                'sub_category_id' => $request->sub_category_id,
                'description' => $request->description,
                'management_ratio' => $request->management_ratio,
                'deposit_ratio' => $request->deposit_ratio,
                'vat' => $request->vat,
                'date' => $request->parent_order?->date ?? $request->date,
                'time' => $request->parent_order?->time ?? $request->time,
                'warranty_end_date' => null
            ]);

            $date = date('Y-m-d H:i:s');
            $locations = [];
            if ($request->has_two_pickup_points) {
                array_push($locations, [
                    'order_id' => $order->id,
                    'type' => '0',
                    'title' => $request->parent_order?->start_title ?? $request->start_title,
                    'latitude' => $request->parent_order?->start_latitude ?? $request->start_latitude,
                    'longitude' => $request->parent_order?->start_longitude ?? $request->start_longitude,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }

            array_push($locations, [
                'order_id' => $order->id,
                'type' => '1',
                'title' => $request->parent_order?->end_title ?? $request->end_title,
                'latitude' => $request->parent_order?->end_latitude ?? $request->end_latitude,
                'longitude' => $request->parent_order?->end_longitude ?? $request->end_longitude,
                'created_at' => $date,
                'updated_at' => $date
            ]);

            OrderLocation::insert($locations);

            $services = [];
            foreach ($request->services as $service_id) {
                array_push($services, [
                    'order_id' => $order->id,
                    'service_id' => $service_id,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }

            OrderService::insert($services);

            $paths = $this->uploadMultipleFiles($request, 'orders');
            $images = [];
            foreach ($paths as $path) {
                array_push($images, [
                    'order_id' => $order->id,
                    'type' => '0',
                    'image' => $path,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }

            OrderImage::insert($images);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(500, __('messages.something_went_wrong'));
        }

        return $this->apiResponse(200, __('messages.create_order'));
    }

    /*----------------------------------------------------------------------------------------------------*/

     public function process(ProcessOrderRequest $request)
    {
        $order = Order::with('subCategory.category.warranty')
            ->findOrFail($request->order_id);

        $currentStatus = $order->status;

        // نجيب القسم الرئيسي
        $category = optional($order->subCategory)->category;

        // نفحص إذا عليه ضمان
        $hasWarranty = (bool) optional($category)->warranty;

        // نحسب أيام الضمان فقط عند الانتقال من 2 → 3
        $warranty_days = ($currentStatus == 2 && $hasWarranty)
            ? OrderSetting::value('warranty_days')
            : 0;

        // الحالة الجديدة
        $newStatus = $currentStatus + 1;

        $order->update([
            'status' => $newStatus,
            'completed_at' => ($newStatus == 3) ? Carbon::now() : null,
            'warranty_end_date' => ($newStatus == 3 && $hasWarranty)
                ? Carbon::now()->addDays($warranty_days)->format('Y-m-d H:i:s')
                : null
        ]);

        if ($newStatus == 3) {
            $images = [];
            $date = now();

            foreach ($this->uploadMultipleFiles($request, 'orders', 'before_images') as $path) {
                $images[] = [
                    'order_id' => $order->id,
                    'type' => 1,
                    'image' => $path,
                    'created_at' => $date,
                    'updated_at' => $date
                ];
            }

            foreach ($this->uploadMultipleFiles($request, 'orders', 'after_images') as $path) {
                $images[] = [
                    'order_id' => $order->id,
                    'type' => 2,
                    'image' => $path,
                    'created_at' => $date,
                    'updated_at' => $date
                ];
            }

            OrderImage::insert($images);
        }

        return $this->apiResponse(
            200,
            __("messages.change_order_status_$newStatus")
        );
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function evaluate(EvaluateOrderRequest $request)
    {
        $order = Order::findOrFail($request->order_id);

        Evaluation::create([
            'order_id' => $order->id,
            'client_id' => $order->client_id,
            'worker_id' => $order->worker_id,
            'rating' => $request->rating,
            'message' => $request->message
        ]);

        return $this->apiResponse(200, __('messages.evaluate_order'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function cancel(CancelOrderRequest $request)
    {
        $order = Order::findOrFail($request->order_id);

        $order->update([
            'status' => 4
        ]);

        return $this->apiResponse(200, __('messages.cancel_order'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function statuses()
    {
        $statuses = range(0, 4);
        $result = [];

        $userId = auth()->id();

        foreach ($statuses as $status) {
            $hasUnread = (Notification::where([['user_id', $userId], ['read', 0]])
                ->whereHasMorph('notifiable', [Order::class], fn($query) => $query->where('status', $status))
                ->exists()
                ||
                Chat::join('orders', 'orders.id', '=', 'chats.order_id')
                ->join('messages', 'messages.chat_id', '=', 'chats.id')
                ->where(function ($query) use ($userId) {
                    $query->where('orders.worker_id', $userId)
                        ->orWhere('orders.client_id', $userId);
                })
                ->where([
                    ['messages.read', 0],
                    ['messages.user_id', '!=', $userId],
                    ['orders.status', $status],
                ])
                ->exists()) ? 1 : 0;

            $result["status_{$status}"] = $hasUnread;
        }

        return $this->apiResponse(200, 'statuses', null, $result);
    }
}

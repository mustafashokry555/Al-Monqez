<?php

namespace App\Http\Controllers\Api\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\OrderHelper;
use App\Http\Requests\Api\StoreApp\Orders\CancelOrderRequest;
use App\Http\Requests\Api\StoreApp\Orders\EvaluateOrderRequest;
use App\Http\Requests\Api\StoreApp\Orders\MakeOrderRequest;
use App\Http\Requests\Api\StoreApp\Orders\NotifyOrderRequest;
use App\Http\Requests\Api\StoreApp\Orders\ProcessOrderRequest;
use App\Http\Resources\StoreApp\OrderResource;
use App\Models\StoreCoupon;
use App\Models\StoreEvaluation;
use App\Models\StoreOrder;
use App\Models\StoreOrderProduct;
use App\Models\StoreProduct;
use App\Models\StoreSetting;
use App\Models\StoreUsedCoupon;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
class OrderController extends Controller
{
    use ApiResponse, OrderHelper;

    public function index(Request $request)
    {
        $orders = StoreOrder::query()->select(
            'store_orders.id',
            'users.name AS store_name',
            'users.image AS store_image',
            'store_orders.total',
            'store_orders.status',
            'store_orders.created_at'
        )
            ->join('users', 'users.id', '=', 'store_orders.store_id');

        if (auth()->user()->role_id == 5) {
            $orders->addSelect(
                'store_orders.address',
                'clients.phone AS client_phone'
            )
                ->withCount('products')
                ->join('users AS clients', 'clients.id', '=', 'store_orders.user_id')
                ->where(function ($query) {
                    $query->where('store_orders.driver_id', auth()->id())
                        ->orWhere(function ($query) {
                            $query->where('store_orders.status', 2)
                                ->whereNull('store_orders.driver_id');
                        });
                });
        } else {
            $orders->where('store_orders.user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $orders->where('store_orders.status', $request->status);
        }

        $orders = $orders->orderBy('created_at', 'desc')
            ->get();

        return $this->apiResponse(200, 'orders', null, OrderResource::collection($orders));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $language = app()->getLocale();
        $order = StoreOrder::query()->select(
            'store_orders.id',
            'store_orders.store_id',
            'users.name AS store_name',
            'users.image AS store_image',
            'store_orders.address',
            'store_orders.latitude',
            'store_orders.longitude',
            'store_orders.vat',
            'store_orders.delivery_charge',
            'store_used_coupons.coupon_code',
            'store_used_coupons.discount_percentage',
            'store_used_coupons.max_discount_amount',
            'store_orders.sub_total',
            'store_orders.total',
            'store_orders.status',
        'store_orders.payment_method',
        'store_orders.payment',
            'store_orders.created_at'
        )
            ->with([
                'products' => function ($query) use ($language) {
                    $query->select(
                        'store_products.id',
                        'store_order_products.order_id',
                        "store_products.name_$language AS name",
                        'store_products.image',
                        'store_order_products.quantity',
                        'store_order_products.price'
                    )
                        ->join('store_products', 'store_products.id', '=', 'store_order_products.product_id');
                }
            ])
            ->join('users', 'users.id', '=', 'store_orders.store_id')
            ->leftJoin('store_used_coupons', 'store_used_coupons.order_id', '=', 'store_orders.id');

        if (auth()->user()->role_id == '4') {
            $order->addSelect('drivers.name AS driver_name', 'drivers.image AS driver_image', 'drivers.phone AS driver_phone')
                ->leftJoin('users AS drivers', 'drivers.id', '=', 'store_orders.driver_id');
        } else if (auth()->user()->role_id == '5') {
            $order->addSelect('clients.name AS client_name', 'clients.image AS client_image', 'clients.phone AS client_phone')
                ->join('users AS clients', 'clients.id', '=', 'store_orders.user_id');
        }

        if (auth()->user()->role_id == 5) {
            $order->where(function ($query) {
                $query->where('store_orders.driver_id', auth()->id())
                    ->orWhere(function ($query) {
                        $query->where('store_orders.status', 2)
                            ->whereNull('store_orders.driver_id');
                    });
            });
        } else {
            $order->where('store_orders.user_id', auth()->id());
        }

        $order = $order->findOrFail($id);

        return $this->apiResponse(200, 'order', null, new OrderResource($order));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function make(MakeOrderRequest $request)
    {
        DB::beginTransaction();
       try {
            $products = StoreProduct::select(
                'store_products.id',
                'store_products.price',
                'store_products.sale_price',
                'store_carts.quantity'
            )
                ->join('store_carts', 'store_carts.product_id', '=', 'store_products.id')
                ->where('store_products.store_id', $request->store_id)
                ->where([['store_products.displayed', '1']])
                ->where('store_carts.user_id', auth()->id())
                ->get();
            $coupon = $request->coupon_code ? StoreCoupon::select('id', 'code', 'discount_percentage', 'max_discount_amount')->where('code', $request->coupon_code)->first() : null;
            $orderSummary = $this->calcOrderSummary($products, $coupon);
            $orderSetting = StoreSetting::select('management_ratio')->first();


    if($request->payment_method == 'online')
    {
    $order = StoreOrder::create([
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'payment' => 1,
                'user_id' => $request->user()->id,
                'store_id' => $request->store_id,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'management_ratio' => $orderSetting->management_ratio,
                'vat' => $orderSummary['vat_percentage'],
                'delivery_charge' => $orderSummary['delivery_charge'],
                'sub_total' => $orderSummary['sub_total_price'],
                'total' => $orderSummary['total_price']
            ]);
    }
    else
    {
    $order = StoreOrder::create([
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'user_id' => $request->user()->id,
                'store_id' => $request->store_id,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'management_ratio' => $orderSetting->management_ratio,
                'vat' => $orderSummary['vat_percentage'],
                'delivery_charge' => $orderSummary['delivery_charge'],
                'sub_total' => $orderSummary['sub_total_price'],
                'total' => $orderSummary['total_price']
            ]);
    }
       

            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                StoreProduct::where('id', $product->id)->decrement('quantity', $product->quantity);
            }
            StoreOrderProduct::insert($data);
$coupon_discount_value=0;
            if ($coupon) {
                StoreUsedCoupon::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'coupon_id' => $coupon->id,
                    'coupon_code' => $coupon->code,
                    'discount_percentage' => $coupon->discount_percentage,
                    'max_discount_amount' => $coupon->max_discount_amount
                ]);
            }
        
        	if ($request->payment_method == "tabby") {

    DB::commit(); // مهم جداً قبل الاستدعاء الخارجي

    $tabbyDetails = $this->tabbyPayment(
     $request, $order->id

    );

    return $this->apiResponse(200, 'بانتظار مراجعة الدفع', $tabbyDetails);
}
       
       
           if ($request->payment_method == "tamara") {

        DB::commit(); // لازم نحفظ الطلب قبل نرسل الطلب لتمارا

       

        $tamaraDetails = $this->tamaraPayment(
          $request, $order->id
        );

        // لو تمارا رجعت رابط، نرجعه للتطبيق
        if (isset($tamaraDetails['checkout_url'])) {
            return $this->apiResponse(200, 'بانتظار مراجعة الدفع', $tamaraDetails);
        } else {
            // لو صار خطأ، نرجع رسالة الخطأ
            return $this->apiResponse(400, $tamaraDetails['error'] ?? 'خطأ في الاتصال بتمارا');
        }
    }
       

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(500, __('messages.something_went_wrong'));
        }
DB::commit();
        return $this->apiResponse(200, __('messages.create_order'));
    }



	public function tabbyPayment(Request $request, $order_id)
{
    $user = request()->user();
 
   
     $language = app()->getLocale();
// استرجاع الطلب مع المنتجات
$order = StoreOrder::query()
    ->select(
        'store_orders.id',
        'store_orders.store_id',
        'users.name AS store_name',
        'users.image AS store_image',
        'store_orders.address',
        'store_orders.latitude',
        'store_orders.longitude',
        'store_orders.vat',
        'store_orders.delivery_charge',
        'store_used_coupons.coupon_code',
        'store_used_coupons.discount_percentage',
        'store_used_coupons.max_discount_amount',
        'store_orders.sub_total',
        'store_orders.total',
        'store_orders.status',
        'store_orders.created_at'
    )
    ->with([
        'products' => function ($query) use ($language) {
            $query->select(
                'store_products.id',
                'store_order_products.order_id',
                "store_products.name_$language AS name",
                'store_products.image',
                'store_order_products.quantity',
                'store_order_products.price'
            )
            ->join('store_products', 'store_products.id', '=', 'store_order_products.product_id');
        }
    ])
    ->join('users', 'users.id', '=', 'store_orders.store_id')
    ->leftJoin('store_used_coupons', 'store_used_coupons.order_id', '=', 'store_orders.id')
    ->where('store_orders.id', $order_id)
    ->first(); // استرجاع طلب واحد
if (!$order) {
    return response()->json(['error' => 'Order not found'], 404);
}
// تحويل المنتجات إلى نفس شكل orderHistoryItems
$orderHistoryItems = [];
 $total_price=$order->total;
 $shippingAmount = $order->delivery_charge ?? 0;
    $taxAmount = $order->vat ?? 0;
    $discountAmount = 0;
	

if ($order && $order->products) {
    foreach ($order->products as $product) {
        $orderHistoryItems[] = [
            "title" => $product->name,
            "description" => $product->description ?? '',
            "quantity" => $product->quantity,
            "unit_price" => (string) $product->price,
            "discount_amount" => "0.00",
            "reference_id" => (string) $product->id,
            "image_url" => $product->imageLink ?? "http://example.com",
            "product_url" => "http://example.com",
            "ordered" => 0,
            "captured" => 0,
            "shipped" => 0,
            "refunded" => 0,
            "gender" => "Male",
            "category" => "string",
            "color" => "string",
            "product_material" => "string",
            "size_type" => "string",
            "size" => "string",
            "brand" => "string"
        ];
    }
}


    // إعداد الحمولة
    $payload = [
        "payment" => [
            "amount" => (string) $total_price,
            "currency" => "SAR",
            "description" => "Order from MMS",
            "webhook_url" => "https://almonqez-alshamel.com/api/store-app/webhooks/tabby", 
            "buyer" => [
                "phone" => $user->mobile,
                "email" => $user->email ?? "buyer@yahoo.com",
                "name" => "hamid 01",
                "dob" => "1999-08-28"
            ],
            "shipping_address" => [
                "city" => request()->city ?? "nasr city",
                "address" => request()->address,
                "zip" => "29956"
            ],
            "order" => [
                "tax_amount" => (string) $taxAmount,
                "shipping_amount" => (string) $shippingAmount,
                "discount_amount" => (string) $discountAmount,
                "updated_at" => now()->toIso8601String(),
                "reference_id" => (string) $order_id
            ],
            "buyer_history" => [
                "registered_since" => $user->created_at->toIso8601String(),
                "loyalty_level" => 1,
                "wishlist_count" => 1,
                "is_social_networks_connected" => true,
                "is_phone_number_verified" => true,
                "is_email_verified" => true
            ],
            "order_history" => [[
                "purchased_at" => now()->toIso8601String(),
                "amount" => (string) $total_price,
                "payment_method" => "card",
                "status" => "new",
                "buyer" => [
                    "phone" => $user->mobile,
                    "email" => $user->email ?? "buyer@yahoo.com",
                    "name" => $user->name,
                    "dob" => "1995-08-24"
                ],
                "shipping_address" => [
                    "city" => request()->city ?? "monib",
                    "address" => request()->address ?? "giza, Egypt",
                    "zip" => "1455"
                ],
                "items" => $orderHistoryItems
            ]],
            "meta" => [
                "order_id" => $order_id,
                "customer" => null
            ],
            "attachment" => [
                "body" => json_encode([
                    "flight_reservation_details" => [
                        "pnr" => "TR9088999",
                        "itinerary" => [],
                        "insurance" => [],
                        "passengers" => [],
                        "affiliate_name" => "some affiliate"
                    ]
                ]),
                "content_type" => "application/vnd.tabby.v1+json"
            ]
        ],
        "lang" => "ar",
        "merchant_code" => "MMS",
        "merchant_urls" => [
            "success" => "https://almonqez-alshamel.com/api/store-app/tabby/success",
            "cancel" => "https://almonqez-alshamel.com/api/store-app/tabby/cancel",
            "failure" => "https://almonqez-alshamel.com/api/store-app/tabby/failure"
        ],
    ];

    // إرسال الطلب إلى Tabby
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . env('TABBY_API_KEY_TEST'),
    ])->post('https://api.tabby.ai/api/v2/checkout', $payload);

    // معالجة الاستجابة
    $responseData = $response->json();
    
    // التحقق من وجود web_url
    if(isset($responseData['payment']['status']) && $responseData['payment']['status'] === 'CREATED')
    {
        if (isset($responseData['configuration']['available_products']['installments'][0]['web_url'])) {
            $webUrl = $responseData['configuration']['available_products']['installments'][0]['web_url'];
            $qr_code = $responseData['configuration']['available_products']['installments'][0]['qr_code'];
           return [
    'web_url' => $webUrl,
    'qr_code' => $qr_code
];

        

        
        } else {
            return response()->json([
                'error' => 'لم يتم العثور على رابط الدفع.'
            ], 400);
			
			
        }
    } else{
         return response()->json([
                'error' => __('dashboard.'.$responseData['configuration']['products']['installments']['rejection_reason'])
            ], 400);
    }
    
}

public function tamaraPayment(Request $request, $order_id)
{
    $user = request()->user();
    $language = app()->getLocale();

    // 1. جلب بيانات الطلب والمنتجات (نفس طريقتك في tabbyDetails)
    $order = StoreOrder::query()
        ->select(
            'store_orders.id',
            'store_orders.store_id',
            'users.name AS store_name',
            'store_orders.address',
            'store_orders.vat',
            'store_orders.delivery_charge',
            'store_orders.total',
            'store_orders.created_at'
        )
        ->with([
            'products' => function ($query) use ($language) {
                $query->select(
                    'store_products.id',
                    'store_order_products.order_id',
                    "store_products.name_$language AS name",
                    'store_products.image',
                    'store_order_products.quantity',
                    'store_order_products.price'
                )
                ->join('store_products', 'store_products.id', '=', 'store_order_products.product_id');
            }
        ])
        ->join('users', 'users.id', '=', 'store_orders.store_id')
        ->where('store_orders.id', $order_id)
        ->first();
if (!$order) {
    return response()->json(['error' => 'Order not found'], 404);
}
 $total_price=$order->total;
 $shippingAmount = $order->delivery_charge ?? 0;
    $taxAmount = $order->vat ?? 0;
    $discountAmount = 0;
	
    // 2. تجهيز قائمة المنتجات (Items) بتنسيق تمارا
    $items = [];
    if ($order && $order->products) {
        foreach ($order->products as $product) {
            $items[] = [
                "reference_id" => (string) $product->id,
                "type" => "Digital", // أو Physical حسب نوع منتجاتك
                "name" => $product->name,
                "sku" => (string) $product->id,
                "quantity" => (int) $product->quantity,
                "unit_price" => [
                    "amount" => (float) $product->price,
                    "currency" => "SAR"
                ],
                "total_amount" => [
                    "amount" => (float) ($product->price * $product->quantity),
                    "currency" => "SAR"
                ],
                // تمارا تتطلب رابط صورة ومنتج
                "image_url" => $product->imageLink ?? url('images/default.png'), 
                "product_url" => url('/') 
            ];
        }
    }

    // 3. تجهيز الـ Payload الكامل
    $payload = [
        "order_reference_id" => (string) $order_id,
        "total_amount" => [
            "amount" => (float) $total_price,
            "currency" => "SAR"
        ],
        "description" => "Order #" . $order_id,
        "country_code" => "SA", // كود الدولة (SA أو AE)
        "payment_type" => "PAY_BY_INSTALMENTS", // نوع الدفع (تقسيط)
        "locale" => "ar-SA",
        "items" => $items,
        "consumer" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "email" => $user->email ?? "test@test.com",
            "phone" => $user->mobile
        ],
        "billing_address" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "line1" => $order->address ?? "Address",
            "city" => request()->city ?? "Riyadh",
            "country_code" => "SA"
        ],
        "shipping_address" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "line1" => $order->address ?? "Address",
            "city" => request()->city ?? "Riyadh",
            "country_code" => "SA"
        ],
        // روابط الرجوع (عدلها لروابط موقعك الحقيقية)
        "merchant_url" => [
            "success" => "https://almonqez-alshamel.com/api/store-app/tamara/success",
            "failure" => "https://almonqez-alshamel.com/api/store-app/tamara/failure",
            "cancel" => "https://almonqez-alshamel.com/api/store-app/tamara/cancel",
            "notification" => "https://almonqez-alshamel.com/api/store-app/tamara/webhook"
        ],
        // بيانات إضافية (الشحن والضريبة والخصم)
        "shipping_amount" => [
            "amount" => (float) $shippingAmount,
            "currency" => "SAR"
        ],
        "tax_amount" => [
            "amount" => (float) $taxAmount,
            "currency" => "SAR"
        ],
        "discount" => [
            "name" => "Coupon Discount",
            "amount" => [
                "amount" => (float) $discountAmount,
                "currency" => "SAR"
            ]
        ]
    ];

    // 4. إرسال الطلب لتمارا
    $response = Http::withToken(env('TAMARA_API_TOKEN'))
        ->post(env('TAMARA_API_URL') . '/checkout', $payload);

    $responseData = $response->json();

    // 5. معالجة الاستجابة
    if ($response->successful() && isset($responseData['checkout_url'])) {
        return [
            'checkout_url' => $responseData['checkout_url'],
            'order_id' => $responseData['order_id'] // رقم الطلب عند تمارا
        ];
    } else {
        // إرجاع الخطأ لمعرفة السبب
        return [
            'error' => $responseData['message'] ?? json_encode($responseData)
        ];
    }
}

    /*----------------------------------------------------------------------------------------------------*/

    public function process(ProcessOrderRequest $request)
    {
        $order = StoreOrder::findOrFail($request->order_id);

        $order->update([
            'driver_id' => $order->driver_id ?? auth()->id(),
            'status' => ++$order->status
        ]);

        return $this->apiResponse(200, __('messages.change_order_status'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function evaluate(EvaluateOrderRequest $request)
    {
        $order = StoreOrder::findOrFail($request->order_id);

        StoreEvaluation::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'store_id' => $order->store_id,
            'rating' => $request->rating
        ]);

        return $this->apiResponse(200, __('messages.evaluate_order'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function cancel(CancelOrderRequest $request)
    {
        $order = StoreOrder::findOrFail($request->order_id);

        $order->update([
            'status' => 5
        ]);

        return $this->apiResponse(200, __('messages.cancel_order'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function notify(NotifyOrderRequest $request)
    {
        $deviceTokens = User::select('device_token')
            ->join('store_orders', 'store_orders.user_id', '=', 'users.id')
            ->whereNotNull('device_token')
            ->where('role_id', 4)
            ->where('store_orders.id', $request->order_id)
            ->pluck('device_token')
            ->all();

        $notificationService = new FirebaseService();
        $notificationService->notify(
            __('messages.update_order'),
            __('messages.notify_order_message', ['ORDER_ID' => $request->order_id]),
            $deviceTokens,
            [
                'order_id' => $request->order_id,
                'navigation' => 'store_order'
            ]
        );

        return $this->apiResponse(200, __('messages.notify_order'));
    }
}

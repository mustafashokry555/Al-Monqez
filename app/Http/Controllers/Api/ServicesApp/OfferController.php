<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\ServicesApp\Coupons\ApplyCouponRequest;
use App\Http\Requests\Api\ServicesApp\Offers\MakeOfferRequest;
use App\Http\Requests\Api\ServicesApp\Offers\ProcessOfferRequest;
use App\Http\Resources\ServicesApp\OfferResource;
use App\Models\Order;
use App\Models\OrderRequest;
use App\Models\Partner;
use App\Models\User;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
class OfferController extends Controller
{
    use ApiResponse;

    public function index($id)
    {
        $language = app()->getLocale();
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

        return $this->apiResponse(200, 'offers', null, OfferResource::collection($offers));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $language = app()->getLocale();
        $offer = OrderRequest::select(
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
            'users.image AS worker_image',
        )
            ->addSelect([
                'discount_percentage' => function ($query) use ($request) {
                    $query->select('discount_percentage')
                        ->from('partners')
                        ->where('coupon_code', $request->coupon_code);
                },
                'max_discount_amount' => function ($query) use ($request) {
                    $query->select('max_discount_amount')
                        ->from('partners')
                        ->where('coupon_code', $request->coupon_code);
                }
            ])
            ->join('orders', 'order_requests.order_id', '=', 'orders.id')
            ->join('users', 'order_requests.worker_id', '=', 'users.id')
            ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->where([['order_requests.id', $request->offer_id], ['orders.status', '0'], ['orders.client_id', auth()->id()]])
            ->whereNotNull('order_requests.price')
            ->first();

        return $this->apiResponse(200, __('messages.offer_applied'), null, new OfferResource($offer));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function make(MakeOfferRequest $request)
    {
        $request->order_request->update([
            'price' => $request->price
        ]);

        return $this->apiResponse(200, __('messages.send_offer'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function process(ProcessOfferRequest $request)
    {
  
        if ($request->status == '0') {
            $request->order_request->update([
                'price' => null
            ]);
        } else {
            $userRole = User::select('role_id')->findOrFail($request->order_request->worker_id)->role_id;
            $order = Order::findOrFail($request->order_request->order_id);
            $order->update([
                'payment_type' => $request->payment_type,
                'transaction_id' => $request->transaction_id,
                'company_id' => ($userRole == '7') ? $request->order_request->worker_id : null,
                'worker_id' => ($userRole == '3') ? $request->order_request->worker_id : null,
                'total' => $request->order_request->price,
                'status' => 1,
				'payment_method' => $request->payment_method,
            ]);

            if ($request->coupon_code) {
                $coupon = Partner::where('coupon_code', $request->coupon_code)->first();
                UserCoupon::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'coupon_id' => $coupon->id,
                    'coupon_code' => $coupon->coupon_code,
                    'discount_percentage' => $coupon->discount_percentage,
                    'max_discount_amount' => $coupon->max_discount_amount
                ]);
            }
			
			if ($request->payment_method == "tabby") { 
			 $tabbyDetails = $this->tabbyPaymentOffer(
             $request,  $request->offer_id

    );

    return $this->apiResponse(200, 'بانتظار مراجعة الدفع', $tabbyDetails);
			} 
			if ($request->payment_method == "tamara") { 
			
			   $tamaraDetails = $this->tamaraPaymentOffer(
       $request,  $request->offer_id
        );

        // لو تمارا رجعت رابط، نرجعه للتطبيق
        if (isset($tamaraDetails['checkout_url'])) {
            return $this->apiResponse(200, 'بانتظار مراجعة الدفع', $tamaraDetails);
        } else {
            // لو صار خطأ، نرجع رسالة الخطأ
            return $this->apiResponse(400, $tamaraDetails['error'] ?? 'خطأ في الاتصال بتمارا');
        }
			}
        }

        return $this->apiResponse(200, __("messages.process_offer_$request->status"));
    }
	
	

	
public function tabbyPaymentOffer(Request $request, $offer_id = null)
{


    $user = request()->user();
    $language = app()->getLocale();

// جلب العرض للحصول على السعر ورقم الطلبية
    $orderRequest = OrderRequest::findOrFail($offer_id);
    $total_price = $orderRequest->price;
    $order_id = $orderRequest->order_id;




    // جلب بيانات الطلب من Order (طلبات الخدمات)
    $order = Order::query()
        ->select(
            'orders.id',
            'orders.total',
            'orders.vat',
            'orders.deposit_ratio',
            'orders.date',
            'orders.time',
          //  'orders.address',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
        )
        ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
        ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
        ->where('orders.id', $order_id)
        ->first();

    if (!$order) {
        return ['error' => 'Order not found'];
    }

  
    $taxAmount = $order->vat ?? 0;
    $shippingAmount = 0; // الخدمات عادةً ما عندها شحن
    $discountAmount = 0;
    // تجهيز المنتجات (الخدمة نفسها كـ item واحد)
    $orderHistoryItems = [
        [
            "title" => $order->category_name . ' - ' . $order->sub_category_name,
            "description" => "Service Order #" . $order_id,
            "quantity" => 1,
            "unit_price" => (string) $total_price,
            "discount_amount" => "0.00",
            "reference_id" => (string) $order_id,
            "image_url" => "http://example.com",
            "product_url" => "http://example.com",
            "ordered" => 1,
            "captured" => 0,
            "shipped" => 0,
            "refunded" => 0,
            "gender" => "Male",
            "category" => "Services",
            "color" => "",
            "product_material" => "",
            "size_type" => "",
            "size" => "",
            "brand" => ""
        ]
    ];

    $payload = [
        "payment" => [
            "amount" => (string) $total_price,
            "currency" => "SAR",
            "description" => "Service Order from MMS",
         "webhook_url" => "https://almonqez-alshamel.com/api/tabbyoffer/webhooks/tabby", 
            "buyer" => [
                "phone" => $user->phone ?? $user->mobile,
                "email" => $user->email ?? "buyer@yahoo.com",
                "name" => $user->name,
                "dob" => "1999-08-28"
            ],
            "shipping_address" => [
                "city" => $request->city ?? "Riyadh",
                "address" => $order->address ?? $request->address ?? "Address",
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
                "wishlist_count" => 0,
                "is_social_networks_connected" => false,
                "is_phone_number_verified" => true,
                "is_email_verified" => true
            ],
            "order_history" => [[
                "purchased_at" => now()->toIso8601String(),
                "amount" => (string) $total_price,
                "payment_method" => "card",
                "status" => "new",
                "buyer" => [
                    "phone" => $user->phone ?? $user->mobile,
                    "email" => $user->email ?? "buyer@yahoo.com",
                    "name" => $user->name,
                    "dob" => "1995-08-24"
                ],
                "shipping_address" => [
                    "city" => $request->city ?? "Riyadh",
                    "address" => $order->address ?? "Address",
                    "zip" => "29956"
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
                        "affiliate_name" => "MMS"
                    ]
                ]),
                "content_type" => "application/vnd.tabby.v1+json"
            ]
        ],
        "lang" => $language == 'ar' ? 'ar' : 'en',
        "merchant_code" => "MMS",
        "merchant_urls" => [
            "success" => "https://almonqez-alshamel.com/api/tabbyoffer/tabby/success",
            "cancel" => "https://almonqez-alshamel.com/api/tabbyoffer/tabby/cancel",
            "failure" => "https://almonqez-alshamel.com/api/tabbyoffer/tabby/failure"
        ],
    ];


    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . env('TABBY_API_KEY_TEST'),
    ])->post('https://api.tabby.ai/api/v2/checkout', $payload);

    $responseData = $response->json();

    if (isset($responseData['payment']['status']) && $responseData['payment']['status'] === 'CREATED') {
        if (isset($responseData['configuration']['available_products']['installments'][0]['web_url'])) {
            return [
                'web_url' => $responseData['configuration']['available_products']['installments'][0]['web_url'],
                'qr_code' => $responseData['configuration']['available_products']['installments'][0]['qr_code'] ?? null,
            ];
        }
        return ['error' => 'لم يتم العثور على رابط الدفع.'];
    }

    return [
        'error' => $responseData['configuration']['products']['installments']['rejection_reason'] ?? 'خطأ في الاتصال بـ Tabby'
    ];
}

/*----------------------------------------------------------------------------------------------------*/

public function tamaraPaymentOffer(Request $request, $offer_id)
{
    $user = request()->user();
    $language = app()->getLocale();

  $orderRequest = OrderRequest::findOrFail($offer_id);
    $total_price = $orderRequest->price;
    $order_id = $orderRequest->order_id;

    // جلب بيانات الطلب من Order (طلبات الخدمات)
    $order = Order::query()
        ->select(
            'orders.id',
            'orders.total',
            'orders.vat',
           // 'orders.address',
            'orders.created_at',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS sub_category_name",
        )
        ->join('sub_categories', 'orders.sub_category_id', '=', 'sub_categories.id')
        ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
        ->where('orders.id', $order_id)
        ->first();

    if (!$order) {
        return ['error' => 'Order not found'];
    }

  
    $taxAmount = $order->vat ?? 0;
    $shippingAmount = 0;
    $discountAmount = 0;

    // الخدمة كـ item واحد
    $items = [
        [
            "reference_id" => (string) $order_id,
            "type" => "Digital",
            "name" => $order->category_name . ' - ' . $order->sub_category_name,
            "sku" => "SERVICE-" . $order_id,
            "quantity" => 1,
            "unit_price" => [
                "amount" => (float) $total_price,
                "currency" => "SAR"
            ],
            "total_amount" => [
                "amount" => (float) $total_price,
                "currency" => "SAR"
            ],
            "image_url" => url('images/default.png'),
            "product_url" => url('/')
        ]
    ];

    $payload = [
        "order_reference_id" => (string) $order_id,
        "total_amount" => [
            "amount" => (float) $total_price,
            "currency" => "SAR"
        ],
        "description" => "Service Order #" . $order_id,
        "country_code" => "SA",
        "payment_type" => "PAY_BY_INSTALMENTS",
        "locale" => $language == 'ar' ? "ar-SA" : "en-US",
        "items" => $items,
        "consumer" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "email" => $user->email ?? "test@test.com",
            "phone" => $user->phone ?? $user->mobile
        ],
        "billing_address" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "line1" => $order->address ?? "Address",
            "city" => $request->city ?? "Riyadh",
            "country_code" => "SA"
        ],
        "shipping_address" => [
            "first_name" => $user->name ?? "Customer",
            "last_name" => " ",
            "line1" => $order->address ?? "Address",
            "city" => $request->city ?? "Riyadh",
            "country_code" => "SA"
        ],
        "merchant_url" => [
            "success" => "https://almonqez-alshamel.com/api/tamaraoffer/success",
            "failure" => "https://almonqez-alshamel.com/api/tamaraoffer/failure",
            "cancel" => "https://almonqez-alshamel.com/api/tamaraoffer/cancel",
            "notification" => "https://almonqez-alshamel.com/api/tamaraoffer/webhook"
        ],
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

    $response = Http::withToken(env('TAMARA_API_TOKEN'))
        ->post(env('TAMARA_API_URL') . '/checkout', $payload);

    $responseData = $response->json();

    if ($response->successful() && isset($responseData['checkout_url'])) {
        return [
            'checkout_url' => $responseData['checkout_url'],
            'order_id' => $responseData['order_id']
        ];
    }

    return [
        'error' => $responseData['message'] ?? json_encode($responseData)
    ];
}	
	
	

}

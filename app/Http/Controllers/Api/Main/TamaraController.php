<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StoreOrder;

class TamaraController extends Controller
{
    private string $apiToken;
    private string $apiUrl;

    public function __construct()
    {
      $this->apiToken = env('TAMARA_API_TOKEN');
    // الرابط الأساسي بدون v1
    $this->apiUrl = rtrim(env('TAMARA_API_URL', 'https://api-sandbox.tamara.co'), '/');
    }

    /**
     * Webhook - استقبال التحديثات من تمارا
     */
    public function handle(Request $request)
    {
        $orderStatus = $request->input('order_status');
        $eventType = $request->input('event_type');
        $tamaraOrderId = $request->input('order_id');
        $myOrderId = $request->input('order_reference_id');

        Log::info('🔔 Tamara Webhook Received', [
            'status' => $orderStatus,
            'event' => $eventType,
            'tamara_id' => $tamaraOrderId
        ]);

        try {
            // حالة الموافقة
            if ($orderStatus === 'approved' || $eventType === 'order_approved') {
                return $this->handleOrderApproved($request);
            }

            // حالات الإلغاء أو الفشل
            if (in_array($orderStatus, ['declined', 'expired', 'canceled'])) {
                StoreOrder::where('id', $myOrderId)->update(['payment' => 0]);
                return response()->json(['message' => 'Status updated']);
            }

            return response()->json(['message' => 'Handled']);
        } catch (\Exception $e) {
            Log::error('❌ Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

private function handleOrderApproved(Request $request)
{
    // 1. محاولة جلب رقم تمارا بكل المسميات الممكنة
    $tamaraOrderId = $request->input('order_id')
                     ?? $request->input('tamara_id')
                     ?? $request->input('full_payload.order_id');

    // 2. محاولة جلب رقم طلبك الخاص
    $myOrderId = $request->input('order_reference_id')
                 ?? $request->input('my_order_id');

    Log::info('🔍 Processing Approval', [
        'detected_tamara_id' => $tamaraOrderId,
        'detected_my_id' => $myOrderId
    ]);

    $order = null;

    // 3. البحث عن الطلب في قاعدة البيانات (الأولوية لرقم تمارا لأنه أضمن)
    if ($tamaraOrderId) {
        $order = StoreOrder::where('tamara_order_id', $tamaraOrderId)->first();
    }

    // إذا لم نجده، نبحث برقم الطلب العادي
    if (!$order && $myOrderId) {
        $order = StoreOrder::find($myOrderId);
    }

    if (!$order) {
        Log::error('❌ Order Not Found in Database', ['tamara_id' => $tamaraOrderId]);
        return response()->json(['message' => 'Order not found'], 404);
    }

    // 4. تنفيذ الـ Authorise
    if ($this->authoriseOrder($tamaraOrderId)) {
        Log::info('✅ Authorised Success: ' . $tamaraOrderId);

        // 5. تنفيذ الـ Capture
        if ($this->captureOrder($tamaraOrderId, $order)) {
            Log::info('✅✅ Captured Success: ' . $tamaraOrderId);

            // 6. التحديث الفعلي لقاعدة البيانات
            $order->update([
                'payment' => 1,
                'tamara_order_id' => $tamaraOrderId
            ]);

            Log::info('💰 Database Updated: Payment = 1');
            return response()->json(['message' => 'Payment Completed']);
        } else {
            Log::error('❌ Capture Failed for: ' . $tamaraOrderId);
        }
    } else {
        Log::error('❌ Authorise Failed for: ' . $tamaraOrderId);
    }

    return response()->json(['message' => 'Process failed'], 400);
}

    /**
     * الخطوة الأولى: تفويض العملية
     */
  /**
     * الخطوة الأولى: تفويض العملية
     */
    private function authoriseOrder($orderId)
    {
        // الرابط: /v1/orders/{order_id}/authorise
        $response = Http::withToken($this->apiToken)
            ->post("{$this->apiUrl}/orders/{$orderId}/authorise");

        // 👇 هذا هو التعديل الهام جداً لمعرفة سبب المشكلة
        Log::info('Tamara Authorise Full Response', [
            'order_id' => $orderId,
            'status' => $response->status(),
            'body' => $response->json() // هنا سيخبرنا تمارا لماذا فشل (مثلاً: رصيد غير كافٍ أو حالة الطلب خطأ)
        ]);

        return $response->successful();
    }

    /**
     * الخطوة الثانية: تحصيل المبلغ (هنا التعديل الجوهري)
     */
private function captureOrder($orderId, $order)
{
    $payload = [
        "order_id" => $orderId,
        "total_amount" => [
            "amount" => (float) $order->total,
            "currency" => "SAR"
        ],
        "shipping_amount" => [
            "amount" => (float) ($order->delivery_charge ?? 0),
            "currency" => "SAR"
        ],
        "tax_amount" => [
            "amount" => (float) ($order->vat ?? 0),
            "currency" => "SAR"
        ]
    ];

    // التعديل: إزالة v1 من المسار نهائياً ليصبح متوافقاً مع Checkout و Authorise
    $response = Http::withToken($this->apiToken)
        ->post("{$this->apiUrl}/payments/capture", $payload);

    Log::info('Tamara Capture Response (No V1)', [
        'status' => $response->status(),
        'body' => $response->json()
    ]);

    return $response->successful();
}

/**
     * رابط النجاح - Redirect من صفحة تمارا بعد الدفع
     */
    public function success(Request $request)
    {
        $tamaraOrderId = $request->input('order_id');

        // البحث عن الطلب باستخدام رقم تمارا للتأكد
        $order = StoreOrder::where('tamara_order_id', $tamaraOrderId)->first();

        if ($order) {
            // ملاحظة: الويب هوك غالباً سيكون قد حدث الحالة لـ 1 بالفعل
            return response()->json([
                'status' => true,
                'message' => 'تمت عملية الدفع بنجاح',
                'order_id' => $order->id
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'شكراً لك، تم استلام الدفعة'
        ]);
    }

    /**
     * رابط الفشل
     */
    public function failure(Request $request)
    {
        Log::warning('Tamara Payment Failed Redirect', $request->all());
        return response()->json([
            'status' => false,
            'message' => 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى'
        ]);
    }

    /**
     * رابط الإلغاء
     */
    public function cancel(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'تم إلغاء عملية الدفع'
        ]);
    }
}

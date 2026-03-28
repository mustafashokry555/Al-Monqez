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
        // استخدام مفاتيح تمارا من ملف .env
        $this->apiToken = env('TAMARA_API_TOKEN');
        $this->apiUrl = env('TAMARA_API_URL', 'https://api-sandbox.tamara.co');
    }

    /**
     * Handle Tamara webhook requests
     */
    public function handle(Request $request)
    {
        // تمارا ترسل عادة event_type أو order_status
        $eventType = $request->input('event_type');
        $orderStatus = $request->input('order_status');
        
        Log::info('Tamara webhook received', [
            'event_type' => $eventType, 
            'status' => $orderStatus, 
            'payload' => $request->all()
        ]);

        try {
            // التحقق من نجاح الدفع (order_approved)
            if ($eventType === 'order_approved' || $orderStatus === 'approved') {
                return $this->handleOrderApproved($request);
            }

            // حالات الفشل أو الإلغاء
            if (in_array($orderStatus, ['declined', 'expired', 'canceled'])) {
                /* 
                $this->updateOrderStatus(
                    $request->input('order_reference_id'),
                    3 // failed
                ); 
                */
                return response()->json(['message' => 'Payment failed or canceled']);
            }

            Log::warning('Unhandled webhook status', ['status' => $orderStatus]);
            return response()->json(['message' => 'Unhandled webhook status'], 400);

        } catch (\Exception $e) {
            Log::error('Tamara webhook error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Webhook handling error'], 500);
        }
    }

    /**
     * Handle Approved Order (Authorise & Capture)
     */
    private function handleOrderApproved(Request $request)
    {
        $tamaraOrderId = $request->input('order_id');
        $myOrderId = $request->input('order_reference_id');

        // 1. خطوة التفويض (Authorise) - إلزامية فوراً
        $authoriseResponse = $this->authoriseOrder($tamaraOrderId);

        if (!$authoriseResponse) {
            return response()->json(['message' => 'Authorisation failed'], 400);
        }

        // 2. جلب تفاصيل الطلب لمعرفة المبلغ (اختياري، يمكنك جلبه من قاعدة بياناتك)
        // هنا نفترض أنك تريد عمل Capture فوري كما في كود التابي
        // $order = Order::find($myOrderId);
        // $amount = $order->total;
        
        // للتجربة سنفترض مبلغ ثابت أو نمرره، ولكن في الواقع اجلبه من الـ DB
        // $captureResponse = $this->capturePayment($tamaraOrderId, $amount);

        // إذا أردت اتباع نفس نظام التابي (Capture فوري):
        /*
        if ($this->capturePayment($tamaraOrderId, $amount)) {
            $this->updateOrderStatus($myOrderId, 2); // Paid
            return response()->json(['message' => 'Payment captured successfully']);
        }
        */

        // إذا اكتفيت بالتفويض (Authorise) الآن وتؤخر الـ Capture للشحن:
        /*
        $this->updateOrderStatus($myOrderId, 2); // Paid/Authorized
        */
    
        $order = StoreOrder::findOrFail($myOrderId);

        $order->update([
            'payment' => 1,
           
        ]);

        Log::info('Tamara Order Authorised Successfully', ['order_id' => $tamaraOrderId]);

        return response()->json(['message' => 'Order authorised successfully']);
    }

    /**
     * Authorise Order (Required by Tamara)
     */
    private function authoriseOrder($orderId)
    {
        $response = Http::withToken($this->apiToken)
            ->post("{$this->apiUrl}/orders/{$orderId}/authorise");

        if (!$response->successful()) {
            Log::error('Tamara authorise failed', [
                'status' => $response->status(),
                'error'  => $response->json(),
            ]);
            return false;
        }

        return true;
    }

    /**
     * Capture payment
     */
    private function capturePayment($orderId, $amount)
    {
        $response = Http::withToken($this->apiToken)
            ->post("{$this->apiUrl}/payments/capture", [
                'order_id'     => $orderId,
                'total_amount' => [
                    'amount'   => (float) $amount,
                    'currency' => 'SAR'
                ]
            ]);

        if (!$response->successful()) {
            Log::error('Tamara capture failed', [
                'status' => $response->status(),
                'error'  => $response->json(),
            ]);
            return false;
        }

        return true;
    }

    /**
     * Refund payment
     */
    public function refundPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string', // Tamara Order ID
            'amount'   => 'required|numeric',
            'reason'   => 'nullable|string',
        ]);

        $response = Http::withToken($this->apiToken)
            ->post("{$this->apiUrl}/payments/refund", [
                'order_id'     => $validated['order_id'],
                'total_amount' => [
                    'amount'   => (float) $validated['amount'],
                    'currency' => 'SAR'
                ],
                'comment' => $validated['reason'] ?? 'No reason provided',
            ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Refund successful']);
        }

        Log::error('Tamara refund failed', [
            'status' => $response->status(),
            'error'  => $response->json(),
        ]);

        return response()->json(['message' => 'Refund failed'], $response->status());
    }

 /**
     * Handle Success Redirect
     * يتم استدعاؤها عندما يرجع المستخدم بنجاح من تمارا
     */
    public function success(Request $request)
    {
        // يمكنك هنا جلب الطلب وتحديث حالته أو فقط إرجاع رسالة نجاح
        // ملاحظة: الويب هوك (handle) هو الأهم للتحديث، هنا فقط للمستخدم
        
        $orderId = $request->input('order_id'); // تمارا قد ترسل الـ ID هنا
         $order = StoreOrder::findOrFail($orderId);

        $order->update([
            'payment' => 1,
           
        ]);
        // مثال: إرجاع استجابة JSON أو توجيه لصفحة نجاح في الموقع
        return response()->json([
            'status' => true,
            'message' => 'تمت عملية الدفع بنجاح، جاري مراجعة طلبك',
            'order_id' => $orderId
        ]);
        
       
    }

    /**
     * Handle Failure Redirect
     */
    public function failure(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى'
        ]);
    }

    /**
     * Handle Cancel Redirect
     */
    public function cancel(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => 'تم إلغاء عملية الدفع'
        ]);
    }
}
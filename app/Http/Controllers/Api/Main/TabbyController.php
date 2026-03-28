<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\StoreOrder;

class TabbyController extends Controller
{
    private string $apiKey; // متغير لمفتاح الـ API (Capture, Get)
    
    public function __construct()
    {
        // نقوم بجلب مفتاح الـ API فقط لأنه المستخدم في أكثر من مكان
        // مفتاح الويب هوك سنستدعيه مباشرة من env داخل دالة التحقق
        $this->apiKey = env('TABBY_API_KEY_TEST'); 
    }

    public function success(Request $request)
    {
        $paymentId = $request->query('payment_id');
        return response()->json([
            'status'  => 'success',
            'message' => 'تمت عملية الدفع بنجاح، جاري التحقق من الطلب',
            'data'    => ['payment_id' => $paymentId]
        ]);
    }

    public function cancel(Request $request)
    {
        $paymentId = $request->query('payment_id');
        return response()->json([
            'status'  => 'canceled',
            'message' => 'تم إلغاء العملية من قبل المستخدم',
            'data'    => ['payment_id' => $paymentId]
        ]);
    }

    public function failure(Request $request)
    {
        $paymentId = $request->query('payment_id');
        return response()->json([
            'status'  => 'failed',
            'message' => 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى',
            'data'    => ['payment_id' => $paymentId]
        ]);
    }

    public function handleWebhook(Request $request)
    {
        // 1. التحقق من التوقيع
        if (!$this->isValidSignature($request)) {
            Log::warning('Tabby Webhook: Invalid Signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        Log::info('Tabby webhook received', $payload);

        $status = strtoupper($payload['status'] ?? '');

        switch ($status) {
            case 'AUTHORIZED':
                return $this->handleAuthorized($payload);

            case 'CLOSED':
                return response()->json(['message' => 'Payment closed / completed']);

            case 'REJECTED':
            case 'FAILED':
            case 'CANCELED':
                return response()->json(['message' => 'Payment failed or canceled']);

            default:
                Log::warning('Unhandled webhook status', ['status' => $status]);
                return response()->json(['message' => 'Unhandled webhook status'], 400);
        }
    }

    protected function isValidSignature(Request $request)
    {
        // نستخدم القيم من env مباشرة كما طلبت في الإعدادات
        $headerTitle   = env('TABBY_WEBHOOK_HEADER_TITLE_STORE');
        $expectedValue = env('TABBY_WEBHOOK_HEADER_STORE_VALUE');

        // نستخدم $headerTitle لجلب القيمة القادمة من تابي
        // ملاحظة: Laravel يحول اسم الـ Header إلى lowercase عادة، لذا نتأكد
        $receivedValue = $request->header($headerTitle); 

        if (!$receivedValue) {
            Log::warning('Tabby Webhook: Header not found', ['header' => $headerTitle]);
            return false;
        }

        // نقارن القيمة المتوقعة بالقيمة المستلمة
        return hash_equals($expectedValue, $receivedValue);
    }

   protected function handleAuthorized(array $payload)
{
    $paymentId = $payload['id'];
    
    // نجلب التفاصيل للتأكد
    $paymentDetails = $this->getPaymentDetailsFromTabby($paymentId);

    if (!$paymentDetails) {
        // إذا فشل جلب التفاصيل (بسبب المفتاح)، لا نكمل
        return response()->json(['message' => 'Payment verification failed'], 400);
    }

    Log::info('Tabby payment details', $paymentDetails);

    $status = $paymentDetails['status'];
    $captures = $paymentDetails['captures'] ?? [];

    // نتحقق: هل المبلغ تم تحصيله بالفعل؟ (الحالة CLOSED مع وجود captures)
    // أو هل الحالة AUTHORIZED ونحتاج للتحصيل؟
    
    if ($status === 'AUTHORIZED') {
        // الحالة مفوضة ولم يتم التحصيل بعد -> نقوم بالتحصيل
        $captureResult = $this->capturePayment($paymentDetails['id'], $paymentDetails);

        if ($captureResult) {
            $this->updateOrderAsPaid($paymentDetails);
            return response()->json(['message' => 'Payment captured successfully']);
        }
        
        return response()->json(['message' => 'Payment authorized but capture failed'], 400);
    } 
    elseif ($status === 'CLOSED' && count($captures) > 0) {
        // الحالة مغلقة ولكن يوجد تحصيلات -> يعني تم الدفع بنجاح مسبقاً
        // نقوم بتحديث الطلب فقط إذا لم يكن محدثاً
        $this->updateOrderAsPaid($paymentDetails);
        
        return response()->json(['message' => 'Payment already captured and verified']);
    }

    return response()->json(['message' => 'Payment status not captured or invalid'], 400);
}

// دالة مساعدة لتحديث الطلب لتجنب تكرار الكود
protected function updateOrderAsPaid(array $paymentDetails)
{
    $orderRef = $paymentDetails['order']['reference_id'] ?? null;
    
    if ($orderRef) {
        $order = StoreOrder::find($orderRef);
        if ($order && $order->payment != 1) {
            $order->update(['payment' => 1]);
            Log::info("Order {$orderRef} marked as paid via Webhook.");
        }
    }
}
    public function capturePayment($paymentId, $data)
    {
        $amount = (string)($data['amount'] ?? "0.00");

        // هنا نستخدم $this->apiKey (المفتاح الصحيح)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey, 
            'Content-Type'  => 'application/json',
        ])->post("https://api.tabby.ai/api/v2/payments/{$paymentId}/captures", [
            'amount'      => $amount,
            'description' => $data['description'] ?? 'Order payment capture',
        ]);

        if (!$response->successful()) {
            Log::error('Tabby capture failed', [
                'status' => $response->status(),
                'error'  => $response->json(),
            ]);
            return false;
        }

        return true;
    }

    public function getPaymentDetailsFromTabby($paymentId)
    {
        // هنا نستخدم $this->apiKey (المفتاح الصحيح)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("https://api.tabby.ai/api/v2/payments/{$paymentId}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Failed to get Tabby payment', [
            'status' => $response->status(),
            'error'  => $response->json(),
        ]);

        return null;
    }
}
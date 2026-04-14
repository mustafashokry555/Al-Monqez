<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    private $url = 'https://fcm.googleapis.com/v1/projects/monquez-shaml/messages:send';
    private $scope = 'https://www.googleapis.com/auth/firebase.messaging';
    private $token;

    public function __construct()
    {
        $this->token = Cache::remember('firebase_access_token', 3500, function () {
            $credentials = new ServiceAccountCredentials($this->scope, storage_path('app/firebase.json'));
            return $credentials->fetchAuthToken(HttpHandlerFactory::build());
        });
    }

    /*----------------------------------------------------------------------------------------------------*/

    private function convertArrayValuesToString(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->convertArrayValuesToString($value);
            } else if ($value && !is_object($value)) {
                $value = (string)$value;
            }
        }

        return $array;
    }

    /*----------------------------------------------------------------------------------------------------*/

    private function sendRequest($payload)
    {
        $headers = [
            'Authorization: Bearer ' . $this->token['access_token'],
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 400) {
            Log::error('Firebase notification failed', [
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $error,
                'payload' => $payload,
            ]);
        }
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function notify($title, $body, $devicesTokens, $additionalData = [], $ios_sound = "sound.aiff")
    {
        if ($additionalData) {
            $this->convertArrayValuesToString($additionalData);
        }
        $data = [
            "body" => $body,
            "title" => $title
        ];
        if ($additionalData) {
            $data = array_merge($data, $additionalData);
        }
        foreach ($devicesTokens as $token) {
            // This is a very inefficient idea, but due to time constraints, we will keep it like this for now.
            $user_id = User::where('device_token', $token)->value('id');
            $totalNotifications = Notification::where([['user_id', $user_id], ['read', 0]])
                ->whereHasMorph('notifiable', [Order::class])
                ->count() +
                Chat::join('orders', 'orders.id', '=', 'chats.order_id')
                ->join('messages', 'messages.chat_id', '=', 'chats.id')
                ->where(function ($query) use ($user_id) {
                    $query->where('orders.worker_id', $user_id)
                        ->orWhere('orders.client_id', $user_id);
                })
                ->where([['messages.read', 0], ['messages.user_id', '!=', $user_id]])
                ->count();

            $data['badge'] = "$totalNotifications";
            // This is a very inefficient idea, but due to time constraints, we will keep it like this for now.

            $payload = [
                'message' => [
                    'token' => $token,
                    'data' => $data,
                    'android' => [
                        'priority' => 'high',
                    ],
                    'apns' => [
                        'headers' => [
                            'apns-priority' => '10',
                            'apns-push-type' => 'alert',
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $title,
                                    'body'  => $body,
                                ],
                                'sound' => $ios_sound,
                            ],
                        ],
                    ],
                ],
            ];

            $this->sendRequest($payload);
        }
    }
}

<?php

namespace App\Http\Helpers;

trait Firebase
{
    private function notify($title, $body, $devicesTokens, $additionalData = null)
    {
        $data = [
            'registration_ids' => $devicesTokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'sound.mp3',
            ],
            'data' => $additionalData,
        ];

        $jsonData = json_encode($data);

        $headers = [
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $channel = curl_init();

        curl_setopt($channel, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($channel, CURLOPT_POST, true);
        curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_POSTFIELDS, $jsonData);
        curl_exec($channel);
    }
}

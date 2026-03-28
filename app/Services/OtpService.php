<?php

namespace App\Services;

class OtpService
{
    private $apiKey;
    private $userName;
    private $sender;

    public function __construct()
    {
        $this->apiKey = env('MORA_API_KEY');
        $this->userName = env('MORA_USER_NAME');
        $this->sender = env('MORA_SENDER');
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function sendOtp($phone, $body)
    {
        if (str_starts_with($phone, '+966')) {
            $phone = "0" . substr($phone, 4);
        }

        $url = "https://www.mora-sa.com/api/v1/sendsms?api_key=$this->apiKey&username=$this->userName&message=" . urlencode($body) . "&sender=$this->sender&numbers=$phone&response=text";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_exec($curl);
        curl_close($curl);

        return true;
    }
}

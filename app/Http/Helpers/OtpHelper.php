<?php

namespace App\Http\Helpers;

use App\Models\Otp;

trait OtpHelper
{
    private function sendOtp($user, $type)
    {
        $otp = Otp::updateOrCreate([
            'phone' => $user->phone,
            'type' => $type
        ], [
            'code' => rand(1000, 9999)
        ]);

        $this->otpService->sendOtp($user->phone, __("messages.otp_authentication_$type", ["NAME" => $user->name, "CODE" => $otp->code]));
    }
}

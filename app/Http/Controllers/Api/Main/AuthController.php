<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\FileStorage;
use App\Http\Helpers\OtpHelper;
use App\Http\Requests\Api\Main\Auth\CheckResetPasswordRequest;
use App\Http\Requests\Api\Main\Auth\ConfirmCodeRequest;
use App\Http\Requests\Api\Main\Auth\ForgetPasswordRequest;
use App\Http\Requests\Api\Main\Auth\LoginRequest;
use App\Http\Requests\Api\Main\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Main\Auth\SignupRequest;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserDetail;
use App\Models\UserFile;
use App\Models\UserService;
use App\Models\UserSubCategory;
use App\Models\UserVehicleRegistration;
use App\Models\Wallet;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse, FileStorage, OtpHelper;

    private $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function login(LoginRequest $request)
    {
        $user = User::where([['phone', $request->phone]])->where(function ($query) {
            $query->whereIn('role_id', ['3', '4', '5']);
        })->first();

        if (!$user) {
            return $this->apiResponse(400, __('messages.wrong_password'));
        }

        if (!$user->deleted_at) {
            if (Hash::check($request->password, $user->password)) {
                $user->tokens()->delete();
                $token = $user->createToken('auth-token');

                if ($user->verified == '0') {
                    $this->sendOtp($user, 0);
                }

                if ($request->device_token) {
                    User::where('device_token', $request->device_token)->where('id', '!=', $user->id)->update(['device_token' => null]);
                    $user->update([
                        'device_token' => $request->device_token
                    ]);
                }

                return $this->apiResponse(200, __('messages.login_done'), null, [
                    'access_token' => $token->plainTextToken,
                    'role_id' => (int) $user->role_id,
                    'is_verified' => $user->verified,
                    'is_blocked' => $user->blocked
                ]);
            }

            return $this->apiResponse(400, __('messages.wrong_password'));
        }

        return $this->apiResponse(400, __('messages.deleted_account'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'role_id' =>  $request->role_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $this->uploadFile($request, 'users'),
            'accepted' => ($request->role_id == '3') ? 0 : 1
        ]);

        if ($request->role_id == '3') {
            UserDetail::create([
                'user_id' => $user->id,
                'city_id' => $request->city_id,
                'category_id' => $request->category_id
            ]);

            $data = [];
            foreach ($request->sub_category_ids as $subCategoryId) {
                $data[] = [
                    'user_id' => $user->id,
                    'sub_category_id' => $subCategoryId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserSubCategory::insert($data);

            $data = [];
            foreach ($request->service_ids as $serviceId) {
                $data[] = [
                    'user_id' => $user->id,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserService::insert($data);
        }

        if (in_array($request->role_id, ['3', '5'])) {
            if ($request->is_vehicle_sub_category || $request->is_driver) {
                UserVehicleRegistration::create([
                    'user_id' => $user->id,
                    'id_number' => $request->id_number,
                    'vehicle_license_image' => $this->uploadFile($request, 'licenses', null, 'vehicle_license_image', 'vehicle_license_image'),
                    'driving_license_image' => $this->uploadFile($request, 'licenses', null, 'driving_license_image', 'driving_license_image')
                ]);
            }

            UserAccount::create([
                'user_id' => $user->id,
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number
            ]);

            $paths = $this->uploadMultipleFiles($request, 'vehicle_equipments', 'vehicle_equipment_images');
            $data = [];
            foreach ($paths as $path) {
                $data[] = [
                    'user_id' => $user->id,
                    'file' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            UserFile::insert($data);
        }


        Wallet::create([
            'user_id' => $user->id
        ]);

        $token = $user->createToken('auth-token');

        $this->sendOtp($user, 0);

        if ($request->device_token) {
            User::where('device_token', $request->device_token)->where('id', '!=', $user->id)->update(['device_token' => null]);
            $user->update([
                'device_token' => $request->device_token
            ]);
        }

        return $this->apiResponse(200, __('messages.registration_done'), null, [
            'access_token' => $token->plainTextToken,
            'role_id' => (int) $user->role_id,
            'is_verified' => 0,
            'is_blocked' => 0
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('phone', $request->phone)->select(['phone'])->first();

        $this->sendOtp($user, 1);

        return $this->apiResponse(200, __('messages.send_reset_password_code'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function checkResetPasswordCode(CheckResetPasswordRequest $request)
    {
        return $this->apiResponse(200, __('messages.correct_code'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where([['phone', $request->phone]])->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Otp::where([['phone', $user->phone], ['type', 1], ['code', $request->code]])->delete();

        $user->tokens()->delete();
        $token = $user->createToken('auth-token');

        if ($user->verified == '0') {
            $this->sendOtp($user, 0);
        }

        if ($request->device_token) {
            User::where('device_token', $request->device_token)->where('id', '!=', $user->id)->update(['device_token' => null]);
            $user->update([
                'device_token' => $request->device_token
            ]);
        }

        return $this->apiResponse(200, __('messages.reset_password'), null, [
            'access_token' => $token->plainTextToken,
            'role_id' => (int) $user->role_id,
            'is_verified' => $user->verified,
            'is_blocked' => $user->blocked
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function resendVerifyingOTP()
    {
        $user = auth()->user();

        if ($user->verified) {
            return $this->apiResponse(400, __('messages.account_verified'));
        }

        $this->sendOtp($user, 0);

        return $this->apiResponse(200, __('messages.resend_verify_code'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function verifyAccount(ConfirmCodeRequest $request)
    {
        User::findOrFail($request->user()->id)->update([
            'verified' => 1
        ]);

        Otp::where([['phone', $request->user()->phone], ['type', 0], ['code', $request->code]])->delete();

        return $this->apiResponse(200, __('messages.verify_account'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function logout(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        $request->user()->currentAccessToken()->delete();

        $user->update([
            'device_token' => null
        ]);

        return $this->apiResponse(200, __('messages.logout'));
    }
}

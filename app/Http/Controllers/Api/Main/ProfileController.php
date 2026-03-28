<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Api\Main\Profile\DeleteVehicleEquipmentImageRequest;
use App\Http\Requests\Api\Main\Profile\UpdateActivityLogRequest;
use App\Http\Requests\Api\Main\Profile\UpdateProfileRequest;
use App\Http\Resources\Main\NotificationResource;
use App\Http\Resources\Main\UserResource;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserActivityLog;
use App\Models\UserDetail;
use App\Models\UserFile;
use App\Models\UserService;
use App\Models\UserSubCategory;
use App\Models\UserVehicleRegistration;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponse, FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $user = User::query()->select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.image'
        );

        if (auth()->user()->role_id == '3') {
            $user->addSelect(
                'user_details.city_id',
                'user_details.category_id',
                "categories.name_$language AS category_name",
                'user_details.description'
            )
                ->with([
                    'subCategories' => function ($query) use ($language) {
                        $query->select('sub_categories.id', 'user_sub_categories.user_id', 'user_sub_categories.sub_category_id', 'sub_categories.sub_category_type', "sub_categories.name_$language AS name")
                            ->with(['services' => function ($query) use ($language) {
                                $query->select('services.id', 'services.sub_category_id', "services.name_$language AS name")
                                    ->join('user_services', 'services.id', '=', 'user_services.service_id')
                                    ->where('user_services.user_id', auth()->id());
                            }])
                            ->join('sub_categories', 'sub_categories.id', '=', 'user_sub_categories.sub_category_id');
                    }
                ])
                ->join('user_details', 'users.id', '=', 'user_details.user_id')
                ->join('categories', 'user_details.category_id', '=', 'categories.id');
        }

        if (in_array(auth()->user()->role_id, ['3', '5'])) {
            $user->addSelect(
                'user_vehicle_registrations.id_number',
                'user_vehicle_registrations.vehicle_license_image',
                'user_vehicle_registrations.driving_license_image',
                'user_accounts.bank_name',
                'user_accounts.iban_number'
            )
                ->with('files')
                ->leftJoin('user_vehicle_registrations', function ($join) {
                    $join->on('users.id', '=', 'user_vehicle_registrations.user_id');
                })
                ->leftJoin('user_accounts', function ($join) {
                    $join->on('users.id', '=', 'user_accounts.user_id');
                });
        }

        $user = $user->findOrFail(auth()->id());

        return $this->apiResponse(200, 'profile', null, new UserResource($user));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function update(UpdateProfileRequest $request)
    {
        $user = User::findOrFail(auth()->id());

        $password = $user->password;
        $token = null;
        if ($request->password) {
            $password = Hash::make($request->password);
            $user->tokens()->delete();
            $token = $user->createToken('auth-token');
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $password,
            'image' => $this->uploadFile($request, 'users', $user)
        ]);

        if ($user->role_id == '3') {
            UserDetail::where('user_id', $user->id)->update([
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'description' => $request->description
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
            UserSubCategory::where('user_id', auth()->id())->delete();
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
            UserService::where('user_id', $user->id)->delete();
            UserService::insert($data);
        }

        if (in_array($user->role_id, ['3', '5'])) {
            if ($request->is_vehicle_sub_category || $request->is_driver) {
                $userVehicleRegistration = UserVehicleRegistration::where('user_id', $user->id)->first();
                UserVehicleRegistration::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'id_number' => $request->id_number,
                    'vehicle_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'vehicle_license_image', 'vehicle_license_image'),
                    'driving_license_image' => $this->uploadFile($request, 'licenses', $userVehicleRegistration, 'driving_license_image', 'driving_license_image')
                ]);
            } else {
                $userVehicleRegistration = UserVehicleRegistration::where('user_id', $user->id)->first();
                if ($userVehicleRegistration) {
                    $this->deleteFile($userVehicleRegistration->vehicle_license_image);
                    $this->deleteFile($userVehicleRegistration->driving_license_image);
                }

                UserVehicleRegistration::updateOrCreate([
                    'user_id' => $user->id
                ], [
                    'id_number' => $request->id_number,
                    'vehicle_license_image' => null,
                    'driving_license_image' => null
                ]);
            }

            UserAccount::updateOrCreate([
                'user_id' => $user->id
            ], [
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number
            ]);

            if ($request->vehicle_equipment_images && count($request->vehicle_equipment_images) > 0) {
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
        }

        return $this->apiResponse(200, __('messages.profile_updated'), null, [
            'access_token' => $token->plainTextToken ?? null
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function DeleteVehicleEquipmentImage(DeleteVehicleEquipmentImageRequest $request)
    {
        $userFile = UserFile::where('id', $request->image_id)->firstOrFail();
        $this->deleteFile($userFile->file);
        $userFile->delete();

        return $this->apiResponse(200, __('messages.delete_vehicle_equipment_image'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function delete()
    {
        $user = User::findOrFail(auth()->id());
        $user->tokens()->delete();
        $user->delete();
        $user->update([
            'phone' => $user->phone . '_deleted_' . $user->id,
            'email' => $user->email ? $user->email . '_deleted_' . $user->id : null,
            'device_token' => null
        ]);

        return $this->apiResponse(200, __('messages.profile_deleted'));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function notifications()
    {
        $notifications = Notification::select('title', 'message', 'created_at')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())->orWhere(function ($query) {
                    $query->whereNull('user_id')->where(function ($query) {
                        $query->where('type', '1')->orWhere('type', auth()->user()->role_id - 1);
                    });
                });
            })
            ->where([['created_at', '>=', auth()->user()->created_at]])
            ->latest()
            ->get();

        return $this->apiResponse(200, 'notifications', null, NotificationResource::collection($notifications));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function updateActivityLog(UpdateActivityLogRequest $request)
    {
        UserActivityLog::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'device_type' => $request->device_type,
                'last_active_at' => now(),
                'is_online' => $request->is_online
            ]
        );

        return $this->apiResponse(200, __('messages.activity_log_updated'));
    }
}

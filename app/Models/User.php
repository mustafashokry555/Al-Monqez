<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /*-----------------------------------------------------------------------------------------------*/

    public function subCategories()
    {
        return $this->hasMany(UserSubCategory::class, 'user_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(UserService::class, 'user_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(UserFile::class, 'user_id', 'id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'worker_id', 'id');
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Chat::class, 'user_id', 'chat_id');
    }

    public function new_messages()
    {
        return $this->hasManyThrough(Message::class, Chat::class, 'user_id', 'chat_id')->whereNotNull('messages.user_id')->where('read', '0');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function classifications()
    {
        return $this->hasMany(StoreClassification::class, 'store_id', 'id');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? Storage::url($this->image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }

    public function coverImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cover_image ? Storage::url($this->cover_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }


    public function commercialRegistrationLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->commercial_registration ? Storage::url($this->commercial_registration) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }

    public function licenseLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->license ? Storage::url($this->license) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }


    /*-----------------------------------------------------------------------------------------------*/

    public function vehicleLicenseImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->vehicle_license_image ? Storage::url($this->vehicle_license_image) : null
        );
    }

    public function drivingLicenseImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->driving_license_image ? Storage::url($this->driving_license_image) : null
        );
    }


    /*-----------------------------------------------------------------------------------------------*/

    public static function clientRules()
    {
        return [
            'image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'name' => 'required|string|max:250',
            'phone' => 'required|string|starts_with:+966|min:13|max:13|unique:users',
            'email' => 'nullable|email:filter|max:250|unique:users',
            'password' => 'required|string|min:8|max:25'
        ];
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function workerRules($request)
    {
        $whereConditions = [['role_id', '7']];

        if (optional(auth()->user())->role_id == 7) {
            $whereConditions[] = ['id', auth()->id()];
        }

        return [
            'company_id' => [
                (optional(auth()->user())->role_id == 7) ? 'required' : 'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                }),
            ],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function ($query) {
                    return $query->where('displayed', 1);
                })
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('displayed', 1);
                })
            ],
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => [
                Rule::exists('sub_categories', 'id')->where(function ($query) use ($request) {
                    return $query->where([['category_id', $request->category_id], ['displayed', 1]]);
                })
            ],
            'service_ids' => 'required|array',
            'service_ids.*' => [
                Rule::exists('services', 'id')->where(function ($query) use ($request) {
                    return $query->whereIn('sub_category_id', $request->sub_category_ids)->where('displayed', 1);
                })
            ],
            'description' => 'nullable|string|max:5000'
        ];
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function storeRules()
    {
        $languages = ['ar', 'en', 'ur'];
        $rules = [];

        foreach ($languages as $lang) {
            $rules["address_$lang"] = 'required|string|max:250';
        }

        return array_merge($rules, [
            'city_id' => [
                'required',
                Rule::exists('store_cities', 'id')->where(function ($query) {
                    return $query->where('displayed', 1);
                })
            ],
            'category_id' => [
                'required',
                Rule::exists('store_categories', 'id')->where(function ($query) {
                    return $query->where('displayed', 1);
                })
            ],
            'latitude' => 'required|string|max:250',
            'longitude' => 'required|string|max:250',
            'cover_image' => 'nullable|mimes:png,jpg,jpeg,webp',
            'terms' => 'accepted',
            'commercial_registration' => 'nullable|mimes:png,jpg,jpeg,webp',
            'license' => 'nullable|mimes:png,jpg,jpeg,webp',
            'bank_name' => 'nullable|string|max:250',
            'account_holder_name' => 'nullable|string|max:250',
            'IBAN' => 'nullable|string|max:250',
        ]);
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function vehicleEquipmentRules($request)
    {
        $subCategoryIds = (array) $request->input('sub_category_ids', []);

        $isWorkerContext =
            ($request->routeIs('api.auth.signup') && $request->role_id == '3') ||
            ($request->routeIs('api.profile.update') && optional(auth()->user())->role_id == '3') ||
            $request->routeIs('services_app.admin.workers.store', 'services_app.admin.workers.update');

        $isDriverContext =
            ($request->routeIs('api.auth.signup') && $request->role_id == '5') ||
            ($request->routeIs('api.profile.update') && optional(auth()->user())->role_id == '5') ||
            $request->routeIs('store_app.admin.drivers.store', 'store_app.admin.drivers.update');

        $isVehicleSubCategory = false;

        if ($isWorkerContext && !empty($subCategoryIds)) {
            $isVehicleSubCategory = SubCategory::whereIn('id', $subCategoryIds)
                ->where('sub_category_type', '0')
                ->exists();
        }

        $isDriver = $isDriverContext;

        $request->merge([
            'is_vehicle_sub_category' => $isVehicleSubCategory,
            'is_driver' => $isDriver
        ]);

        return [
            'id_number' => 'required|string|max:250',
            'vehicle_license_image' => [
                Rule::requiredIf($isVehicleSubCategory || $isDriver),
                'nullable',
                'mimes:png,jpg,jpeg,webp'
            ],
            'driving_license_image' => [
                Rule::requiredIf($isVehicleSubCategory || $isDriver),
                'nullable',
                'mimes:png,jpg,jpeg,webp'
            ],
            'vehicle_equipment_images' => 'required|array|max:5',
            'vehicle_equipment_images.*' => 'mimes:png,jpg,jpeg,webp',

        ];
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function bankAccountRules()
    {
        return [
            'bank_name' => 'required|string|max:250',
            'iban_number' => 'required|string|max:250'
        ];
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function permissionRules()
    {
        return [
            'permissions' => 'required|array',
            'permissions.*' => 'required|distinct|exists:permissions,id'
        ];
    }
}

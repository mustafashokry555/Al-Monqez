<?php

namespace App\Http\Requests\Api\Main\Profile;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\User;
use App\Models\UserFile;
use App\Models\UserVehicleRegistration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;
    protected $max_vehicle_equipment_images = 5;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = array_merge(User::clientRules(), [
            'email' => [
                'nullable',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', "!=", auth()->id());
                })
            ],
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', "!=", auth()->id());
                }),
                'starts_with:+966',
                'min:13',
                'max:13'
            ],
            'password' => 'nullable|string|max:50'
        ]);

        if (auth()->user()->role_id == '3') {
            $rules = array_merge($rules, User::workerRules($this));
        }

        if (in_array(auth()->user()->role_id, ['3', '5'])) {
            $userVehicleRegistration = UserVehicleRegistration::select('vehicle_license_image', 'driving_license_image')->where('user_id', auth()->id())->first();
            $is_required = ($this->is_vehicle_sub_category || $this->is_driver);
            $this->max_vehicle_equipment_images = $this->max_vehicle_equipment_images - UserFile::where('user_id', auth()->id())->count();

            $rules = array_merge($rules, User::vehicleEquipmentRules($this), [
                'vehicle_license_image' => [
                    Rule::requiredIf($is_required && !$userVehicleRegistration?->vehicle_license_image),
                    'nullable',
                    'mimes:png,jpg,jpeg,webp'
                ],
                'driving_license_image' => [
                    Rule::requiredIf($is_required && !$userVehicleRegistration?->driving_license_image),
                    'nullable',
                    'mimes:png,jpg,jpeg,webp'
                ],
                'vehicle_equipment_images' => [
                    Rule::requiredIf($is_required && $this->max_vehicle_equipment_images  == 5),
                    'nullable',
                    'array',
                    'max:' . $this->max_vehicle_equipment_images
                ],
            ], User::bankAccountRules());
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            "vehicle_equipment_images.max" => __("messages.max_vehicle_equipment_images", ['MAX' => $this->max_vehicle_equipment_images]),
        ];
    }
}

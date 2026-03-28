<?php

namespace App\Http\Requests\Admin\ServicesApp\Workers;

use App\Models\User;
use App\Models\UserFile;
use App\Models\UserVehicleRegistration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkerRequest extends FormRequest
{
    protected $max_vehicle_equipment_images = 5;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $this->max_vehicle_equipment_images = $this->max_vehicle_equipment_images - UserFile::where('user_id', $this->worker_id)->count();
        $userVehicleRegistration = UserVehicleRegistration::select('vehicle_license_image', 'driving_license_image')->where('user_id', $this->worker_id)->first();

        return array_merge(User::clientRules(), User::workerRules($this), User::vehicleEquipmentRules($this), [
            'worker_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '3');
                })
            ],
            'email' => [
                'nullable',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->worker_id);
                })
            ],
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->worker_id);
                }),
                'starts_with:+966',
                'min:13',
                'max:13'
            ],
            'password' => 'nullable|string|max:50',
            'vehicle_license_image' => [
                Rule::requiredIf($this->is_vehicle_sub_category && !$userVehicleRegistration?->vehicle_license_image),
                'nullable',
                'mimes:png,jpg,jpeg,webp'
            ],
            'driving_license_image' => [
                Rule::requiredIf($this->is_vehicle_sub_category && !$userVehicleRegistration?->driving_license_image),
                'nullable',
                'mimes:png,jpg,jpeg,webp'
            ],
            'vehicle_equipment_images' =>  [
                Rule::requiredIf($this->is_vehicle_sub_category && $this->max_vehicle_equipment_images  == 5),
                'nullable',
                'array',
                'max:' . $this->max_vehicle_equipment_images
            ],
        ]);
    }

    public function messages(): array
    {
        return [
            "vehicle_equipment_images.max" => __("messages.max_vehicle_equipment_images", ['MAX' => $this->max_vehicle_equipment_images]),
        ];
    }
}

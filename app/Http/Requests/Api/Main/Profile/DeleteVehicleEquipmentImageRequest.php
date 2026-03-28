<?php

namespace App\Http\Requests\Api\Main\Profile;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteVehicleEquipmentImageRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;

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
        return [
            'image_id' => [
                'required',
                Rule::exists('user_files', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ]
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Main\Auth;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
        $rules = array_merge(User::clientRules(), [
            'role_id' => 'required|in:3,4,5',
            'terms' => 'required|boolean|accepted'
        ]);

        if ($this->role_id == '3') {
            $rules = array_merge($rules, User::workerRules($this), [
                // 'files' => 'nullable|array',
                // 'files.*' => 'required|mimes:png,jpg,jpeg,web,pdf'
            ]);
        }

        if (in_array($this->role_id, ['3', '5'])) {
            $rules = array_merge($rules, User::vehicleEquipmentRules($this), User::bankAccountRules(), [
                'image' => 'required|mimes:png,jpg,jpeg,webp',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "terms" => __("messages.agree_terms"),
        ];
    }
}

<?php

namespace App\Http\Requests\Api\Main\Contacts;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class AddContactRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    protected $stopOnFirstFailure = true;

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
        return [
            "name" => "required|string|max:250",
            "phone" => "required|string|max:50",
            "email" => "required|string|email:filter|max:250",
            "subject" => "required|string|max:250",
            "message" => "required|string|max:5000",
        ];
    }
}

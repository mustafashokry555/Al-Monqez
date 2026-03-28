<?php

namespace App\Http\Requests\Admin\ServicesApp\Companies;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
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
        return array_merge(User::clientRules(), [
            'company_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '7');
                })
            ],
            'email' => [
                'nullable',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->company_id);
                })
            ],
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->company_id);
                }),
                'starts_with:+966',
                'min:13',
                'max:13'
            ],
            'password' => 'nullable|string|max:50'
        ]);
    }
}

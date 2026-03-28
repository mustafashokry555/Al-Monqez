<?php

namespace App\Http\Requests\Admin\Dashboard\Clients;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
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
        return array_merge(User::clientRules(), [
            'client_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '4');
                })
            ],
            'email' => [
                'nullable',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->client_id);
                })
            ],
            'phone' => [
                'required',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('id', '!=', $this->client_id);
                }),
                'starts_with:+966',
                'min:13',
                'max:13'
            ],
            'password' => 'nullable|string|max:50'
        ]);
    }
}

<?php

namespace App\Http\Requests\Admin\ServicesApp\Workers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateWorkerRequest extends FormRequest
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
        $rules = [
            'worker_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '3');
                })
            ]
        ];

        if (auth()->user()->role_id == '7') {
            $rules['worker_id'] = [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role_id', '3')
                        ->where('company_id', auth()->id());
                })
            ];
        }

        return $rules;
    }
}

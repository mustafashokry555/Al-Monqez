<?php

namespace App\Http\Requests\Admin\ServicesApp\Complaints;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessComplaintRequest extends FormRequest
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
        $whereConditions = [];
        if (in_array($this->status, ['1', '3'])) {
            $whereConditions[] = ['status', '0'];
        } elseif ($this->status == '2') {
            $whereConditions[] = ['status', '1'];
        }

        return [
            'complaint_id' => [
                'required',
                Rule::exists('complaints', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                }),
            ],
            'status' => 'required|integer|min:1|max:3'
        ];
    }
}

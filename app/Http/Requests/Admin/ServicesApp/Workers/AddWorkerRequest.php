<?php

namespace App\Http\Requests\Admin\ServicesApp\Workers;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddWorkerRequest extends FormRequest
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
        return array_merge(User::clientRules(), User::workerRules($this), User::vehicleEquipmentRules($this), [
            'image' => 'required|mimes:png,jpg,jpeg,webp'
        ]);
    }
}

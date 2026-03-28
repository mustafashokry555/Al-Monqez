<?php

namespace App\Http\Requests\Admin\Dashboard\Terms;

use App\Models\Term;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTermRequest extends FormRequest
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
        return array_merge(Term::rules(), [
            'term_id' => 'required|exists:terms,id'
        ]);
    }
}

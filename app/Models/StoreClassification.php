<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class StoreClassification extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public static function rules()
    {
        $languages = ['ar', 'en', 'ur'];

        $whereConditions = [['role_id', 6]];

        if (auth()->user()->role_id == 6) {
            $whereConditions[] = ['id', auth()->id()];
        }

        $rules = [
            'store_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                }),
            ]
        ];

        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
        }

        return $rules;
    }
}

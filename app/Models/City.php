<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public static function rules()
    {
        $languages = ['ar', 'en', 'ur'];

        $rules = [];
        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
        }

        return $rules;
    }
}

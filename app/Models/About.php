<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public static function rules()
    {
        $languages = ['ar', 'en', 'ur'];

        $rules = [];
        foreach ($languages as $lang) {
            $rules["title_$lang"] = 'required|string|max:250';
            $rules["description_$lang"] = 'required|string|max:5000';
        }

        return $rules;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? Storage::url($this->image) : Storage::url('defaults/logo.png')
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public static function rules()
    {
        $languages = ['ar', 'en', 'ur'];

        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
            $rules["brief_$lang"] = 'required|string|max:250';
            $rules["description_$lang"] = 'required|string|max:5000';
        }

        return array_merge($rules, [
            'sub_category_id' => 'required|exists:sub_categories,id',
            'image' => 'required|mimes:png,jpg,jpeg,webp'
        ]);
    }
}

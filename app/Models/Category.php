<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

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

        $rules = [];
        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
        }

        return array_merge($rules, [
            'image' => 'required|mimes:png,jpg,jpeg,webp'
        ]);
    }

   public function warranty()
    {
        return $this->hasOne(WarrantyCategory::class);
    }

    public function isWarranty(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->warranty()->exists()
        );
    }
}

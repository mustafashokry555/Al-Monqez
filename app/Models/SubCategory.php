<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function services()
    {
        return $this->hasMany(Service::class, 'sub_category_id', 'id');
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
            'sub_category_type' => 'required|boolean',
            'location_type' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|mimes:png,jpg,jpeg,webp'
        ]);
    }

 public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
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

    public static function rules() {
        return [
            'link' => 'nullable|string|starts_with:https://,http://|max:250',
            'image' => 'required|mimes:png,jpg,jpeg,webp',
        ];
    }
}

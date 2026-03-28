<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Social extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function iconLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->icon ? Storage::url($this->icon) : Storage::url('defaults/logo.png')
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public static function rules() {
        return [
            'link' => 'required|string|starts_with:https://,http://|max:250',
            'icon' => 'required|mimes:png,jpg,jpeg,webp|max:512',
        ];
    }
}

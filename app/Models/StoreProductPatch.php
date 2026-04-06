<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProductPatch extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'patch_id', 'id');
    }

    // name attribute accessor to return the name in the current app locale
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->attributes["name_$locale"] ?? $this->attributes['name_en'] ?? 'N/A';
    }
}

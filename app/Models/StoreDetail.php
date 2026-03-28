<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StoreDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function CoverImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cover_image ? Storage::url($this->cover_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }

  public function commercialRegistrationLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->commercial_registration ? Storage::url($this->commercial_registration) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }

    public function licenseLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->license ? Storage::url($this->license) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->name)[0] . '.png')
        );
    }
}

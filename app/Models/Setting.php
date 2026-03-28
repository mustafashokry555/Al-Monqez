<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function logoLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->logo ? Storage::url($this->logo) : Storage::url('defaults/logo.png')
        );
    }

 public function storeImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->store_image ? Storage::url($this->store_image) : Storage::url('defaults/logo.png')
        );
    }
    public function servicesImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->services_image ? Storage::url($this->services_image) : Storage::url('defaults/logo.png')
        );
    }
}

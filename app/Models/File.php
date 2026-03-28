<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function pathLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->path ? Storage::url($this->path) : Storage::url('defaults/logo.png')
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function fileExtension(): Attribute
    {
        return Attribute::make(
            get: fn() => strtolower(pathinfo($this->path, PATHINFO_EXTENSION))
        );
    }
}

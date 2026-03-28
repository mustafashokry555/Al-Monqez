<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Evaluation extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function clientImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->client_image ? Storage::url($this->client_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->client_name)[0] . '.png')
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function createdFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->locale(app()->getLocale())->translatedFormat('Y/m/d h:i A')
        );
    }
}

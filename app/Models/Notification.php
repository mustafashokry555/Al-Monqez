<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function createdFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::createFromTimeStamp(strtotime($this->created_at))->locale(app()->getLocale())->diffForHumans()
        );
    }
}

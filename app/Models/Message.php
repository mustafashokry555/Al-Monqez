<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function files()
    {
        return $this->hasMany(File::class);
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function userImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->user_image ? Storage::url($this->user_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->user_name)[0] . '.png')
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function createdAtFormatted(): Attribute
    {
        $language = app()->getLocale();

        return Attribute::make(
            get: fn() => Carbon::createFromTimeStamp(strtotime($this->created_at))->locale($language)->diffForHumans() . " (" . Carbon::createFromTimeStamp(strtotime($this->created_at))->locale($language)->translatedFormat('H:i a') . ")"
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public static function rules()
    {
        return [
            'content' => 'nullable|required_without:files|string|max:5000',
            'files' => 'nullable|required_without:content|array|max:10',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,gif,pdf,mp4'
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function isActive() :Attribute
    {
        return Attribute::make(
            get: fn() => ($this->attributes['valid_until'] >= date('Y-m-d') && $this->attributes['valid_from'] <= date('Y-m-d')) ? 1 : 0
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public static function rules()
    {
        return [
            'name' => 'required|string|max:250',
            'coupon_code' => 'required|string|max:50|unique:partners,coupon_code',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from'
        ];
    }
}

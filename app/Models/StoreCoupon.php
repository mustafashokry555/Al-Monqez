<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class StoreCoupon extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->attributes['valid_until'] >= date('Y-m-d') && $this->attributes['valid_from'] <= date('Y-m-d')) ? 1 : 0
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public static function rules()
    {
        $whereConditions = [['role_id', 6]];

        if (auth()->user()->role_id == 6) {
            $whereConditions[] = ['id', auth()->id()];
        }

        return [
            'store_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                })
            ],
            'coupon_code' => 'required|string|max:50|unique:store_coupons,code',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'max_discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from'
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\StoreProductPatch;

class StoreProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function images()
    {
        return $this->hasMany(StoreProductImage::class, 'product_id', 'id');
    }

    public function patch()
    {
        return $this->belongsTo(StoreProductPatch::class, 'patch_id', 'id');
    }

    /*----------------------------------------------------------------------------------------*/

    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? Storage::url($this->image) : Storage::url('defaults/logo.png')
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public static function rules($request)
    {
        $languages = ['ar', 'en', 'ur'];

        $whereConditions = [['role_id', 6]];

        if (auth()->user()->role_id == 6) {
            $whereConditions[] = ['id', auth()->id()];
        }

        $rules = [
            'store_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($whereConditions) {
                    $query->where($whereConditions);
                }),
            ],
            'classification_id' => [
                'required',
                Rule::exists('store_classifications', 'id')->where(function ($query) use ($request) {
                    $query->where('store_id', $request->store_id);
                }),
            ],
            'patch_id' => 'nullable|exists:store_product_patches,id',
            'image' => 'required|mimes:jpg,jpeg,png,webp',
            'price' => 'required|numeric|min:0|max:1000000',
            'sale_price' => 'nullable|numeric|min:0|max:1000000|lt:price',
            'quantity' => 'required|integer|min:0|max:1000000',
            'images' => 'required|array|max:10',
            'images.*' => 'mimes:jpg,jpeg,png,webp',
        ];

        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
            $rules["description_$lang"] = 'required|string|max:5000';
        }

        return $rules;
    }
}

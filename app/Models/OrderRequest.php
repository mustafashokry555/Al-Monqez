<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function clientImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->client_image ? Storage::url($this->client_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->client_name)[0] . '.png')
        );
    }

    public function workerImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->worker_image ? Storage::url($this->worker_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->worker_name)[0] . '.png')
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function discountAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discount = 0;

                if ($this->discount_percentage !== null && $this->max_discount_amount !== null) {
                    $discount = ($this->price * $this->discount_percentage / 100);
                    $discount = min($discount, $this->max_discount_amount);
                }

                return $discount;
            }
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->price - $this->discount_amount
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function vatValue(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_price * $this->vat / 100,
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function depositPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->discounted_price * $this->deposit_ratio / 100) + $this->vat_value,
        );
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_price + $this->vat_value,
        );
    }
}

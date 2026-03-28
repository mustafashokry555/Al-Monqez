<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StoreOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*----------------------------------------------------------------------------------------*/

    public function products()
    {
        return $this->hasMany(StoreOrderProduct::class, 'order_id', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /*----------------------------------------------------------------------------------------*/

    public function discountAmount(): Attribute
    {
        $discount = 0;
        if ($this->discount_percentage !== null && $this->max_discount_amount !== null) {
            $discount = ($this->sub_total * $this->discount_percentage / 100);
            $discount = min($discount, $this->max_discount_amount);
        }

        return Attribute::make(
            get: fn() => $discount,
        );
    }

    public function discountedTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sub_total - $this->discount_amount,
        );
    }

    public function  vatAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_total * $this->vat / 100,
        );
    }

    public function managementAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->sub_total * $this->management_ratio / 100,
        );
    }

    public function storeDues(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_total - $this->management_amount
        );
    }

    /*----------------------------------------------------------------------------------------*/

    public function storeImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->store_image ? Storage::url($this->store_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->store_name)[0] . '.png')
        );
    }

    public function clientImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->client_image ? Storage::url($this->client_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->client_name)[0] . '.png')
        );
    }

    public function driverImageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->driver_image ? Storage::url($this->driver_image) : ('https://ui-avatars.com/api/?name=' . explode(' ', $this->driver_name)[0] . '.png')
        );
    }
}

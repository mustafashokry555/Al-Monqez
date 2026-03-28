<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /*-----------------------------------------------------------------------------------------------*/
    // Relations
    public function childOrders()
    {
        return $this->hasMany(Order::class, 'parent_order_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(OrderService::class, 'order_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(OrderLocation::class, 'order_id', 'id');
    }

    public function problemImages()
    {
        return $this->hasMany(OrderImage::class, 'order_id', 'id')->where('type', 0);
    }

    public function beforeImages()
    {
        return $this->hasMany(OrderImage::class, 'order_id', 'id')->where('type', 1);
    }

    public function afterImages()
    {
        return $this->hasMany(OrderImage::class, 'order_id', 'id')->where('type', 2);
    }

    public function requests()
    {
        return $this->hasMany(OrderRequest::class, 'order_id', 'id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function chats()
    {
        return $this->hasOne(Chat::class, 'order_id', 'id');
    }

    /*-----------------------------------------------------------------------------------------------*/
    // Calculations

    public function discountAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discount = 0;

                if ($this->discount_percentage !== null && $this->max_discount_amount !== null) {
                    $discount = ($this->total * $this->discount_percentage / 100);
                    $discount = min($discount, $this->max_discount_amount);
                }

                return $discount;
            }
        );
    }

    public function discountedTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->total - $this->discount_amount
        );
    }

    public function managementValue(): Attribute
    {
        return Attribute::make(
            get: fn() =>  $this->discounted_total * $this->management_ratio / 100
        );
    }

    public function vatValue(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_total * $this->vat / 100
        );
    }

    public function depositPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->discounted_total * $this->deposit_ratio / 100) + $this->vat_value
        );
    }

    public function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_total + $this->vat_value
        );
    }

    public function ePaidAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->payment_type == '0' ? $this->deposit_price : $this->total_price
        );
    }

    public function cashPaidAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->total_price - $this->e_paid_amount
        );
    }

    public function workerDues(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->e_paid_amount - $this->vat_value - $this->management_value
        );
    }

    /*-----------------------------------------------------------------------------------------------*/
    // Helpers

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

    public function dateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->date)->locale(app()->getLocale())->translatedFormat('j F')
        );
    }

    public function timeFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->time)->locale(app()->getLocale())->translatedFormat('h:i A')
        );
    }
public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }


public function acceptedRequest()
{
    return $this->hasOne(OrderRequest::class, 'order_id', 'id')
        ->whereColumn('order_requests.worker_id', 'orders.worker_id');
}
}

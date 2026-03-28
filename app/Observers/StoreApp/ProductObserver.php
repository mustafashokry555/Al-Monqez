<?php

namespace App\Observers\StoreApp;

use App\Http\Helpers\FileStorage;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;

class ProductObserver
{
    use FileStorage;

    /**
     * Handle the product "deleting" event.
     */
    public function deleting(StoreProduct $product): void
    {
        $images = StoreProductImage::where('product_id', $product->id)->get();
        foreach ($images as $image) {
            $this->deleteFile($image->path);
        }
    }
}

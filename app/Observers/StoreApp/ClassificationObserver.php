<?php

namespace App\Observers\StoreApp;

use App\Http\Helpers\FileStorage;
use App\Models\StoreClassification;
use App\Models\StoreProduct;

class ClassificationObserver
{
    use FileStorage;

    /**
     * Handle the classification "deleting" event.
     */
    public function deleting(StoreClassification $classification): void
    {
        $products = StoreProduct::with('images')->where('classification_id', $classification->id)->get();
        foreach ($products as $product) {
            foreach ($product->images as $image) {
                $this->deleteFile($image->path);
            }
            $this->deleteFile($product->image);
        }
    }
}

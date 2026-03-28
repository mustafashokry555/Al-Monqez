<?php

namespace App\Observers\StoreApp;

use App\Http\Helpers\FileStorage;
use App\Models\StoreDetail;
use App\Models\StoreProduct;
use App\Models\User;
use Symfony\Component\HttpKernel\HttpCache\Store;

class StoreObserver
{
    use FileStorage;

    /**
     * Handle the User "force deleting" event.
     */
    public function forceDeleting(User $user): void
    {
        if ($user->role_id == '6') {
            $storeDetail = StoreDetail::where('store_id', $user->id)->first();
            if ($storeDetail) {
                $this->deleteFile($storeDetail->cover_image);
            }

            $products = StoreProduct::with('images')->where('store_id', $user->id)->get();
            foreach ($products as $product) {
                foreach ($product->images as $image) {
                    $this->deleteFile($image->path);
                }
                $this->deleteFile($product->image);
            }
        }
    }
}

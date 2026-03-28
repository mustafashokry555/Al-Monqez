<?php

namespace App\Observers\ServicesApp;

use App\Http\Helpers\FileStorage;
use App\Models\Service;
use App\Models\SubCategory;

class SubCategoryObserver
{
    use FileStorage;

    /**
     * Handle the SubCategory "deleting" event.
     */
    public function deleting(SubCategory $subCategory): void
    {
        $services = Service::select('image')->where('sub_category_id', $subCategory->id)->get();

        foreach ($services as $service) {
            $this->deleteFile($service->image);
        }
    }
}

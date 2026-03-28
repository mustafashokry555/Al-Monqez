<?php

namespace App\Observers\ServicesApp;

use App\Http\Helpers\FileStorage;
use App\Models\Category;
use App\Models\Service;
use App\Models\SubCategory;
use Illuminate\Support\Arr;

class CategoryObserver
{
    use FileStorage;

    /**
     * Handle the Category "deleting" event.
     */
    public function deleting(Category $category): void
    {
        $subCategories = SubCategory::select('id', 'image')->where('category_id', $category->id)->get();

        foreach ($subCategories as $subCategory) {
            $this->deleteFile($subCategory->image);
        }

        $services = Service::select('image')->whereIn('sub_category_id', Arr::pluck($subCategories, 'id'))->get();

        foreach ($services as $service) {
            $this->deleteFile($service->image);
        }
    }
}

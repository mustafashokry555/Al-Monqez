<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\SubCategories\AddSubCategoryRequest;
use App\Http\Requests\Admin\ServicesApp\SubCategories\UpdateSubCategoryRequest;
use App\Http\Requests\Admin\ServicesApp\SubCategories\ValidateSubCategoryRequest;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $subCategories = SubCategory::select(
            'sub_categories.id',
            'sub_categories.sub_category_type',
            'sub_categories.location_type',
            "categories.name_$language AS category_name",
            "sub_categories.name_$language AS name",
            'sub_categories.image',
            'sub_categories.displayed'
        )
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->paginate(10);

        return view('admin.services-app.sub-categories.index', compact('subCategories'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function all(Request $request)
    {
        $language = app()->getLocale();
        $subCategories = SubCategory::select('id', 'sub_category_type', "name_$language AS name")
            ->with('services', function ($query) use ($language) {
                $query->select('id', 'sub_category_id', "name_$language AS name")
                    ->where('displayed', 1);
            })
            ->where('category_id', $request->category_id)
            ->where('displayed', 1)
            ->get();

        return response()->json($subCategories);
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $language = app()->getLocale();
        $categories = Category::select('id', "name_$language AS name")->get();
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.sub-categories.create', compact('categories', 'languages'));
    }

    public function store(AddSubCategoryRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'sub_category_type' => $request->sub_category_type,
            'location_type' => $request->location_type,
            'category_id' => $request->category_id,
            'image' => $this->uploadFile($request, 'sub-categories'),
            'displayed' => ($request->show) ? 1 : 0
        ]);

        SubCategory::create($data);

        session()->flash('success', __('messages.add_sub_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $language = app()->getLocale();
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::select('id', "name_$language AS name")->get();
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.sub-categories.edit', compact('subCategory', 'categories', 'languages'));
    }

    public function update(UpdateSubCategoryRequest $request)
    {
        $subCategory = SubCategory::findOrFail($request->sub_category_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'sub_category_type' => $request->sub_category_type,
            'location_type' => $request->location_type,
            'category_id' => $request->category_id,
            'image' => $this->uploadFile($request, 'sub-categories', $subCategory)
        ]);

        $subCategory->update($data);

        session()->flash('success', __('messages.edit_sub_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateSubCategoryRequest $request)
    {
        $subCategory = SubCategory::findOrFail($request->sub_category_id);

        $subCategory->update([
            'displayed' => ($subCategory->displayed == '1') ? 0 : 1
        ]);

        session()->flash('success', ($subCategory->displayed == '1') ? __('messages.show_sub_category') : __('messages.hide_sub_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateSubCategoryRequest $request)
    {
        $subCategory = SubCategory::findOrFail($request->sub_category_id);
        $subCategory->delete();
        $this->deleteFile($subCategory->image);

        session()->flash('success', __('messages.delete_sub_category'));
        return redirect()->back();
    }
}

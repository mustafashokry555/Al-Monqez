<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Categories\AddCategoryRequest;
use App\Http\Requests\Admin\ServicesApp\Categories\UpdateCategoryRequest;
use App\Http\Requests\Admin\ServicesApp\Categories\ValidateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $categories = Category::select('id', "name_$language AS name", 'image', 'displayed')->paginate(10);

        return view('admin.services-app.categories.index', compact('categories'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.categories.create', compact('languages'));
    }

    public function store(AddCategoryRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'image' => $this->uploadFile($request, 'categories'),
            'displayed' => ($request->show) ? 1 : 0
        ]);

        Category::create($data);

        session()->flash('success', __('messages.add_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.categories.edit', compact('category', 'languages'));
    }

    public function update(UpdateCategoryRequest $request)
    {
        $category = Category::findOrFail($request->category_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'image' => $this->uploadFile($request, 'categories', $category)
        ]);

        $category->update($data);

        session()->flash('success', __('messages.edit_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateCategoryRequest $request)
    {
        $category = Category::findOrFail($request->category_id);

        $category->update([
            'displayed' => ($category->displayed == '1') ? 0 : 1
        ]);

        session()->flash('success', ($category->displayed == '1') ? __('messages.show_category') : __('messages.hide_category'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateCategoryRequest $request)
    {
        $category = Category::findOrFail($request->category_id);
        $category->delete();
        $this->deleteFile($category->image);

        session()->flash('success', __('messages.delete_category'));
        return redirect()->back();
    }
}

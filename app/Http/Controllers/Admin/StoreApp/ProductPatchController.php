<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreProductPatch;

class ProductPatchController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $patches = StoreProductPatch::orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.store-app.patches.index', compact('patches'));
    }

    public function create()
    {
        $languages = ['ar', 'en'];
        return view('admin.store-app.patches.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $languages = ['ar', 'en'];
        $rules = [];
        foreach ($languages as $lang) {
            $rules["name_$lang"] = 'required|string|max:250';
        }
        $rules['show'] = 'nullable|in:on';

        $validated = $request->validate($rules);

        $data = [];
        foreach ($languages as $lang) {
            $data["name_$lang"] = $validated["name_$lang"];
        }
        $data['displayed'] = ($request->has('show') && $request->show == 'on') ? 1 : 0;

        StoreProductPatch::create($data);

        session()->flash('success', __('messages.add_patch'));
        return redirect(route('store_app.admin.patches.create'));
    }

    public function edit($id)
    {
        $patch = StoreProductPatch::findOrFail($id);
        $languages = ['ar', 'en'];
        return view('admin.store-app.patches.edit', compact('patch', 'languages'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'classification_id' => 'required|exists:store_product_patches,id',
            'name_ar' => 'required|string|max:250',
            'name_en' => 'required|string|max:250',
        ]);

        $patch = StoreProductPatch::findOrFail($request->classification_id);

        $patch->update([
            'name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
        ]);

        session()->flash('success', __('messages.edit_patch'));
        return redirect()->back();
    }

    public function display(Request $request)
    {
        $request->validate(['classification_id' => 'required|exists:store_product_patches,id']);
        $patch = StoreProductPatch::findOrFail($request->classification_id);
        $patch->update(['displayed' => ($patch->displayed == 1) ? 0 : 1]);
        session()->flash('success', ($patch->displayed == 1) ? __('messages.show_patch') : __('messages.hide_patch'));
        return redirect()->back();
    }


    public function destroy(Request $request)
    {
        $request->validate(['classification_id' => 'required|exists:store_product_patches,id']);
        // Deleting the patch will set related products.patch_id to null by DB FK (ON DELETE SET NULL)
        StoreProductPatch::findOrFail($request->classification_id)->delete();
        session()->flash('success', __('messages.delete_patch'));
        return redirect()->back();
    }
}

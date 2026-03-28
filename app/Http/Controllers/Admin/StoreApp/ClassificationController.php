<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApp\Classifications\AddClassificationRequest;
use App\Http\Requests\Admin\StoreApp\Classifications\UpdateClassificationRequest;
use App\Http\Requests\Admin\StoreApp\Classifications\ValidateClassificationRequest;
use App\Models\StoreClassification;
use App\Models\User;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $classifications = StoreClassification::query()->select(
            'store_classifications.id',
            "users.name AS store_name",
            "store_classifications.name_$language AS name",
            'store_classifications.displayed'
        )
            ->join('users', 'users.id', '=', 'store_classifications.store_id');

        if (auth()->user()->role_id == 6) {
            $classifications->where('store_classifications.store_id', auth()->id());
        }

        $classifications = $classifications->paginate(10);

        return view('admin.store-app.classifications.index', compact('classifications'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function getClassificationsByStore(Request $request)
    {
        $store_id = $request->input('store_id');
        $language = app()->getLocale();

        $classifications = StoreClassification::select(
            'id',
            "name_$language AS name"
        )
            ->where('store_id', (auth()->user()->role_id == 6) ? auth()->id() : $store_id)
            ->where('displayed', 1)
            ->get();

        return response()->json([
            'classifications' => $classifications
        ]);
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];
        $stores = User::query()->select('id', 'name')->where('role_id', '6');

        if (auth()->user()->role_id == 6) {
            $stores->where('id', auth()->id());
        }

        $stores = $stores->get();

        return view('admin.store-app.classifications.create', compact('languages', 'stores'));
    }

    public function store(AddClassificationRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'store_id' => $request->store_id,
            'displayed' => ($request->show) ? 1 : 0
        ]);

        StoreClassification::create($data);

        session()->flash('success', __('messages.add_classification'));
        return redirect(route('store_app.admin.classifications.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $classification = StoreClassification::query();
        $languages = ['ar', 'en', 'ur'];
        $stores = User::query()->select('id', 'name')->where('role_id', '6');
        if (auth()->user()->role_id == 6) {
            $classification->where('store_id', auth()->id());
            $stores->where('id', auth()->id());
        }
        $classification = $classification->findOrFail($id);
        $stores = $stores->get();

        return view('admin.store-app.classifications.edit', compact('classification', 'languages', 'stores'));
    }

    public function update(UpdateClassificationRequest $request)
    {
        $classification = StoreClassification::findOrFail($request->classification_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
        }

        $data = array_merge($data, [
            'store_id' => $request->store_id
        ]);

        $classification->update($data);

        session()->flash('success', __('messages.edit_classification'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateClassificationRequest $request)
    {
        $classification = StoreClassification::findOrFail($request->classification_id);

        $classification->update([
            'displayed' => ($classification->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($classification->displayed == 1) ? __('messages.show_classification') : __('messages.hide_classification'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateClassificationRequest $request)
    {
        StoreClassification::findOrFail($request->classification_id)->delete();

        session()->flash('success', __('messages.delete_classification'));
        return redirect()->back();
    }
}

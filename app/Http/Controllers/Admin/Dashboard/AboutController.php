<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dashboard\Abouts\AddAboutRequest;
use App\Http\Requests\Admin\Dashboard\Abouts\UpdateAboutRequest;
use App\Http\Requests\Admin\Dashboard\Abouts\ValidateAboutRequest;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $abouts = About::select('id', "title_$language AS title", 'displayed')->paginate(10);

        return view('admin.dashboard.abouts.index', compact('abouts'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];

        return view('admin.dashboard.abouts.create', compact('languages'));
    }

    public function store(AddAboutRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["title_$lang"] = $request->input("title_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $data = array_merge($data, [
            'displayed' => ($request->show) ? 1 : 0
        ]);

        About::create($data);

        session()->flash('success', __('messages.add_about'));
        return redirect(route('admin.abouts.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $about = About::findOrFail($id);
        $languages = ['ar', 'en', 'ur'];

        return view('admin.dashboard.abouts.edit', compact('about', 'languages'));
    }

    public function update(UpdateAboutRequest $request)
    {
        $about = About::findOrFail($request->about_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["title_$lang"] = $request->input("title_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $about->update($data);

        session()->flash('success', __('messages.edit_about'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateAboutRequest $request)
    {
        $about = About::findOrFail($request->about_id);

        $about->update([
            'displayed' => ($about->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($about->displayed == 1) ? __('messages.show_about') : __('messages.hide_about'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateAboutRequest $request)
    {
        About::findOrFail($request->about_id)->delete();

        session()->flash('success', __('messages.delete_about'));
        return redirect()->back();
    }
}

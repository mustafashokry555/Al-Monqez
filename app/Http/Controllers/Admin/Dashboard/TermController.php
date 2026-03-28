<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dashboard\Terms\AddTermRequest;
use App\Http\Requests\Admin\Dashboard\Terms\UpdateTermRequest;
use App\Http\Requests\Admin\Dashboard\Terms\ValidateTermRequest;
use App\Models\Term;

class TermController extends Controller
{
    public function index()
    {
        $language = app()->getLocale();
        $terms = Term::select('id', "title_$language AS title", 'displayed')->paginate(10);

        return view('admin.dashboard.terms.index', compact('terms'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $languages = ['ar', 'en', 'ur'];

        return view('admin.dashboard.terms.create', compact('languages'));
    }

    public function store(AddTermRequest $request)
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

        Term::create($data);

        session()->flash('success', __('messages.add_term'));
        return redirect(route('admin.terms.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $term = Term::findOrFail($id);
        $languages = ['ar', 'en', 'ur'];

        return view('admin.dashboard.terms.edit', compact('term', 'languages'));
    }

    public function update(UpdateTermRequest $request)
    {
        $term = Term::findOrFail($request->term_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["title_$lang"] = $request->input("title_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $term->update($data);

        session()->flash('success', __('messages.edit_term'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateTermRequest $request)
    {
        $term = Term::findOrFail($request->term_id);

        $term->update([
            'displayed' => ($term->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($term->displayed == 1) ? __('messages.show_term') : __('messages.hide_term'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateTermRequest $request)
    {
        Term::findOrFail($request->term_id)->delete();

        session()->flash('success', __('messages.delete_term'));
        return redirect()->back();
    }
}

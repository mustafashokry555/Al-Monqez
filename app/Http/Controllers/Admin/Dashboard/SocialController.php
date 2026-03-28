<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Socials\AddSocialRequest;
use App\Http\Requests\Admin\ServicesApp\Socials\UpdateSocialRequest;
use App\Http\Requests\Admin\ServicesApp\Socials\ValidateSocialRequest;
use App\Models\Social;

class SocialController extends Controller
{
    use FileStorage;

    public function index()
    {
        $socials = Social::select('id', 'link', 'icon', 'displayed')->paginate(10);

        return view('admin.dashboard.socials.index', compact('socials'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.dashboard.socials.create');
    }

    public function store(AddSocialRequest $request)
    {
        Social::create([
            'link' => $request->link,
            'icon' => $this->uploadFile($request, 'socials', null, 'icon', 'icon'),
            'displayed' => ($request->show) ? 1 : 0
        ]);

        session()->flash('success', __('messages.add_social'));
        return redirect(route('admin.socials.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $social = Social::findOrFail($id);

        return view('admin.dashboard.socials.edit', compact('social'));
    }

    public function update(UpdateSocialRequest $request)
    {
        $social = Social::findOrFail($request->social_id);

        $social->update([
            'link' => $request->link,
            'icon' => $this->uploadFile($request, 'socials', $social, 'icon', 'icon')
        ]);

        session()->flash('success', __('messages.edit_social'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateSocialRequest $request)
    {
        $social = Social::findOrFail($request->social_id);

        $social->update([
            'displayed' => ($social->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($social->displayed == 1) ? __('messages.show_social') : __('messages.hide_social'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateSocialRequest $request)
    {
        $social = Social::findOrFail($request->social_id);
        $social->delete();
        $this->deleteFile($social->icon);

        session()->flash('success', __('messages.delete_social'));
        return redirect()->back();
    }
}

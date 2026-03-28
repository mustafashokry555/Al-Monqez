<?php

namespace App\Http\Controllers\Admin\StoreApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\StoreApp\Sliders\AddSliderRequest;
use App\Http\Requests\Admin\StoreApp\Sliders\UpdateSliderRequest;
use App\Http\Requests\Admin\StoreApp\Sliders\ValidateSliderRequest;
use App\Models\StoreSlider as Slider;

class SliderController extends Controller
{
    use FileStorage;

    public function index()
    {
        $sliders = Slider::select('id', 'link', 'image', 'displayed')->paginate(10);

        return view('admin.store-app.sliders.index', compact('sliders'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        return view('admin.store-app.sliders.create');
    }

    public function store(AddSliderRequest $request)
    {
        Slider::create([
            'link' => $request->link,
            'image' => $this->uploadFile($request, 'store-sliders'),
            'displayed' => ($request->show) ? 1 : 0
        ]);

        session()->flash('success', __('messages.add_slider'));
        return redirect(route('store_app.admin.sliders.create'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);

        return view('admin.store-app.sliders.edit', compact('slider'));
    }

    public function update(UpdateSliderRequest $request)
    {
        $slider = Slider::findOrFail($request->slider_id);

        $link = null;
        if ($request->link) {
            $link = $request->link;
        }

        $slider->update([
            'link' => $link,
            'image' => $this->uploadFile($request, 'store-sliders', $slider)
        ]);

        session()->flash('success', __('messages.edit_slider'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateSliderRequest $request)
    {
        $slider = Slider::findOrFail($request->slider_id);

        $slider->update([
            'displayed' => ($slider->displayed == 1) ? 0 : 1
        ]);

        session()->flash('success', ($slider->displayed == 1) ? __('messages.show_slider') : __('messages.hide_slider'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateSliderRequest $request)
    {
        $slider = Slider::findOrFail($request->slider_id);
        $slider->delete();
        $this->deleteFile($slider->image);

        session()->flash('success', __('messages.delete_slider'));
        return redirect()->back();
    }
}

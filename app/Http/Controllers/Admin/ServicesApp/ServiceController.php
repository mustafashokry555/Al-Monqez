<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Services\AddServiceRequest;
use App\Http\Requests\Admin\ServicesApp\Services\UpdateServiceRequest;
use App\Http\Requests\Admin\ServicesApp\Services\ValidateServiceRequest;
use App\Models\Service;
use App\Models\SubCategory;

class ServiceController extends Controller
{
    use FileStorage;

    public function index()
    {
        $language = app()->getLocale();
        $services = Service::select(
            'services.id',
            "sub_categories.name_$language AS sub_category_name",
            "services.name_$language AS name",
            "services.brief_$language AS brief",
            'services.image',
            'services.displayed'
        )
            ->join('sub_categories', 'services.sub_category_id', '=', 'sub_categories.id')
            ->paginate(10);

        return view('admin.services-app.services.index', compact('services'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function create()
    {
        $language = app()->getLocale();
        $subCategories = SubCategory::select('id', "name_$language AS name")->get();
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.services.create', compact('subCategories', 'languages'));
    }

    public function store(AddServiceRequest $request)
    {
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
            $data["brief_$lang"] = $request->input("brief_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $data = array_merge($data, [
            'sub_category_id' => $request->sub_category_id,
            'image' => $this->uploadFile($request, 'services'),
            'displayed' => ($request->show) ? 1 : 0
        ]);

        Service::create($data);

        session()->flash('success', __('messages.add_service'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function edit($id)
    {
        $language = app()->getLocale();
        $service = Service::findOrFail($id);
        $subCategories = SubCategory::select('id', "name_$language AS name")->get();
        $languages = ['ar', 'en', 'ur'];

        return view('admin.services-app.services.edit', compact('service', 'subCategories', 'languages'));
    }

    public function update(UpdateServiceRequest $request)
    {
        $service = Service::findOrFail($request->service_id);
        $languages = ['ar', 'en', 'ur'];
        $data = [];

        foreach ($languages as $lang) {
            $data["name_$lang"] = $request->input("name_$lang");
            $data["brief_$lang"] = $request->input("brief_$lang");
            $data["description_$lang"] = $request->input("description_$lang");
        }

        $data = array_merge($data, [
            'sub_category_id' => $request->sub_category_id,
            'image' => $this->uploadFile($request, 'services', $service)
        ]);

        $service->update($data);

        session()->flash('success', __('messages.edit_service'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function display(ValidateServiceRequest $request)
    {
        $service = Service::findOrFail($request->service_id);

        $service->update([
            'displayed' => ($service->displayed == '1') ? 0 : 1
        ]);

        session()->flash('success', ($service->displayed == '1') ? __('messages.show_service') : __('messages.hide_service'));
        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function destroy(ValidateServiceRequest $request)
    {
        $service = Service::findOrFail($request->service_id);
        $service->delete();
        $this->deleteFile($service->image);

        session()->flash('success', __('messages.delete_service'));
        return redirect()->back();
    }
}

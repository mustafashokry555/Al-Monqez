<?php

namespace App\Http\Controllers\Api\Main;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\Main\Contacts\AddContactRequest;
use App\Http\Resources\Main\SettingResource;
use App\Http\Resources\Main\SocialResource;
use App\Models\About;
use App\Models\Contact;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Social;
use App\Models\Term;

class MainController extends Controller
{
    use ApiResponse;

    public function siteSettings()
    {
        $language = app()->getLocale();
        $settings = Setting::select("name_$language AS name", "phone", "email", "logo", "store_image", "services_image", "android_app_link", "ios_app_link", "registration_link", "app_version")->first();
        $coordinates = Region::select('coordinates')->first()?->coordinates ?? [];

        return $this->apiResponse(200, 'site settings', null, [
            'settings' => new SettingResource($settings),
            'coordinates' => $coordinates
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function terms()
    {
        $language = app()->getLocale();
        $terms = Term::select("title_$language AS title", "description_$language AS description")
            ->where([['displayed', '1']])
            ->get();

        return $this->apiResponse(200, 'terms', null, [
            'terms' => $terms
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function abouts()
    {
        $language = app()->getLocale();
        $abouts = About::select("title_$language AS title", "description_$language AS description")
            ->where([['displayed', '1']])
            ->get();

        $socials = Social::select('link', 'icon')->where([['displayed', '1']])->get();

        return $this->apiResponse(200, 'about us', null, [
            'abouts' => $abouts,
            'socials' => SocialResource::collection($socials)
        ]);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function contactUs(AddContactRequest $request)
    {
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'read' => 0
        ]);

        return $this->apiResponse(200, __('messages.contact_id', ['NUM' => $contact->id]));
    }
}

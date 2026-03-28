<?php

namespace App\Http\ViewComposer;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingComposer
{
    public function __construct() {}

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $language = app()->getLocale();

        $setting = Cache::rememberForever('setting', function () use ($language) {
            return Setting::select('*', "name_$language AS name", "closed_message_$language AS closed_message")->first() ?? 0;
        });

        $view->with('setting', $setting);
    }
}

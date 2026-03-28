<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Config::get('app.locale');

        if ($request->has('language')) {
            $locale = $request->query('language');
        } else if ($request->header('Accept-Language') && $request->is('api/*')) {
            $locale = $request->header('Accept-Language');
        }

        if (in_array($locale, ['ar', 'en', 'ur'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

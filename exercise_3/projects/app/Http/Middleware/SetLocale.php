<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale {

    // ---------------------------------------------------------------------------------------------- //

    public function handle(Request $request, Closure $next) {

        $locale = $request -> session() -> get('locale', config('app.locale', 'en'));

        if (in_array($locale, ['en', 'pl', 'tr']) === true) {

            App::setLocale($locale);

            app() -> setLocale($locale);

        }

        return $next($request);

    }

    // ---------------------------------------------------------------------------------------------- //

}

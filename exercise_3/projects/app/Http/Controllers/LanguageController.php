<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller {

    // ---------------------------------------------------------------------------------------------- //

    public function switch(Request $request, string $locale) {

        if (in_array($locale, ['en', 'pl', 'tr']) === true) {

            $request -> session() -> put('locale', $locale);

            $request -> session() -> save();

            app() -> setLocale($locale);

        }

        return redirect() -> back(fallback: route('projects.index'));

    }

    // ---------------------------------------------------------------------------------------------- //

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, $lang)
    {
        $available = ['id', 'en'];

        if (in_array($lang, $available)) {
            session(['lang' => $lang]);
            app()->setLocale($lang);
        }

        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
   public function switch(Request $request) {
        $locale = $request->input('locale');

        if (! in_array($locale, ['en', 'tr', 'bg'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid locale'
            ], 422);
        }

        $cookie = cookie(
            'locale',
            $locale,
            60 * 24 * 365 // 1 year
        );

        return response()->json([
            'success' => true,
            'locale'  => $locale
        ])->withCookie($cookie);
    }
}

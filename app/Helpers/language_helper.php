<?php

use App\Http\Controllers\TranslateController;

if (!function_exists('language_helper')) {
    function language_helper($text, $lang): string
    {
        return (new TranslateController())->translateText($text, $lang);
    }
}

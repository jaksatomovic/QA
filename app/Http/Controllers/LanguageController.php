<?php

namespace App\Http\Controllers;

use \Cache;

class LanguageController
{
    public function __invoke(String $languageCode)
    {
        $seconds_to_cache = 3600;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";

        $languageCode = ($languageCode != 'auto') ? $languageCode : config('app.locale');

        header('Content-Type: text/javascript');
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");

        echo('window.i18n = ');
        require resource_path('lang/' . $languageCode . '.json');
        echo(';');
        exit();
    }
}

<?php


if (!function_exists('locationHelper')) {
    function locationHelper()
    {
        return app()->getLocale();
    }

    function locationPermissionHelper()
    {
        $locale = app()->getLocale();
        if ($locale === 'vi') {
            $locationPermission = 'vi';
        } elseif ($locale === 'cn') {
            $locationPermission = 'zh-CN';
        } else {
            $locationPermission = 'en';
        }

        return $locationPermission;
    }
}

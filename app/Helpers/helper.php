<?php

use App\Models\Settings;

if (!function_exists('returnMessage')) {
    /**
     * @param int $type
     * @param mixed $data
     * @param string $message
     * @return array
     */
    function returnMessage(int $type, int $status, mixed $data, string $message): array
    {
        $locale = app()->getLocale();
        $typeText = $type === 1 ? 'success' : 'error';
        $localizedType = $typeText;

        if ($locale === 'vi') {
            $localizedType = $typeText === 'success' ? 'Thành công' : 'Thất bại';
            $message = language_helper($message, 'vi');
        } elseif ($locale === 'cn') {
            $localizedType = $typeText === 'success' ? '成功' : '失败';
            $message = language_helper($message, 'zh-CN');
        } else {
            $message = language_helper($message, 'en');
        }

        return [
            'lang' => $locale,
            'type' => $localizedType,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
    }
}

if (!function_exists('setting')) {
    function setting()
    {
        return Settings::first();
    }
}

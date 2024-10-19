<?php

if (!function_exists('returnMessage')) {
    /**
     * @param int $type
     * @param mixed $data
     * @param string $message
     * @return array
     */
    function returnMessage(int $type, int $status, mixed $data, string $message): array
    {
        if ($type === 1) {
            $data = [
                'type' => 'success',
                'status' => $status ?? 200,
                'message' => $message,
                'data' => $data,
            ];
        } else {
            $data = [
                'type' => 'error',
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ];
        }

        return $data;
    }
}

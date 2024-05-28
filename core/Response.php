<?php

namespace Core;

class Response
{
    public static function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function handleError(int $errorCode, string $message)
    {
        http_response_code($errorCode);
        self::json(array(
            'error_message' => $message
        ));
    }

    public static function handleSuccess(int $successCode, string $message)
    {
        http_response_code($successCode);
        self::json(array(
            'success_message' => $message
        ));
    }
}
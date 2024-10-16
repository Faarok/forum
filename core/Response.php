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

    public static function handleError(int $errorCode, string $message, string $file = null, int $line = null)
    {
        http_response_code($errorCode);

        if($_ENV['APP_DEBUG'] === 'true')
        {
            self::json(array(
                'message' => $message,
                'file' => $file,
                'line' => $line
            ));
        }
        else
        {
            self::json(array(
                'message' => $message
            ));
        }
    }

    public static function handleSuccess(int $successCode, string $message)
    {
        http_response_code($successCode);
        self::json(array(
            'message' => $message
        ));
    }
}
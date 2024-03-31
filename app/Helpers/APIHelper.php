<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class APIHelper
{
    public static function responseFailed($config = [], $status_code = 500)
    {
        $config = [
            'message' => $config['message'] ?? 'Validation failed.',
            'errors' => $config['errors'] ?? [],
        ];

        throw new HttpResponseException(response()->json([
            'message' => $config['message'],
            'errors' => $config['errors'],
        ], $status_code));
    } 
}
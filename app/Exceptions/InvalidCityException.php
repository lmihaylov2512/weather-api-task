<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Exception;

class InvalidCityException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 422);
    }
}

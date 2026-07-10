<?php

namespace App\Http\Controllers;

abstract class BaseController
{
    /**
     * Return a successful JSON response using a consistent envelope.
     */
    protected function successResponse(mixed $data = null, string $message = 'OK', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error JSON response using a consistent envelope.
     */
    protected function errorResponse(string $message, int $status = 400, mixed $errors = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}

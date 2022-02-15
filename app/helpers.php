<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('response_success')) {
    function response_success($data = []): JsonResponse
    {
        return response()->json($data);
    }
}

if (!function_exists('response_delete_success')) {
    function response_delete_success($data = []): JsonResponse
    {
        return response()->json($data, 204);
    }
}

if (!function_exists('response_created')) {
    function response_created($data = []): JsonResponse
    {
        return response()->json($data, 201);
    }
}

if (!function_exists('response_not_found')) {
    function response_not_found($data = []): JsonResponse
    {
        return response()->json($data, 404);
    }
}

if (!function_exists('response_validation_errors')) {
    function response_validation_errors($data = []): JsonResponse
    {
        return response()->json($data, 422);
    }
}

if (!function_exists('response_unauth')) {
    function response_unauth($data = []): JsonResponse
    {
        return response()->json($data, 401);
    }
}

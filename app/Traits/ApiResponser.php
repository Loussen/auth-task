<?php

namespace App\Traits;

trait ApiResponser{

    protected function successResponse($data, $message = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'statusCode' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($message = null, $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'=>'Error',
            'statusCode' => $code,
            'message' => $message,
            'data' => null
        ], $code);
    }

}

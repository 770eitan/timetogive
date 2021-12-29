<?php

// CommonResponse
namespace App\Traits;

trait CommonResponse
{
    /**
     * sendResponse
     * @param $message string
     * @param $data array
     * @param $status number
     * @param $success boolean
     */
    protected function sendResponse($data = [], string $message = 'Success', int $status = 200, bool $success = true)
    {
        return response([
            'success' => $success,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * sendFailedResponse
     * @param $message string
     * @param $status number
     * @param $success boolean
     */
    protected function sendFailedResponse($errors = '', string $message = 'error', int $status = 422)
    {
        return response([
            'success' => false,
            'data' => null,
            //'message' => $message,
            'message' => $errors,
            //'errors' => $errors,
        ], $status);
    }
}

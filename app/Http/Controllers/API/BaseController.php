<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $status = Response::HTTP_OK)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = Response::HTTP_NOT_FOUND)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Create a new resource.
     *
     * @param  mixed  $resource
     * @param  string $message
     * @return \Illuminate\Http\Response
     */
    public function sendCreated($resource, $message)
    {
        return $this->sendResponse($resource, $message, Response::HTTP_CREATED);
    }
}
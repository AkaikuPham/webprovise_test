<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * Create a new JSON response instance.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = [], $status = Response::HTTP_OK, array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';
        switch ($status) {
            case Response::HTTP_OK:
            case Response::HTTP_CREATED:
            case Response::HTTP_NO_CONTENT:
                $result = $data;
                break;
            default:
                $result['errors'] = $data;
        }

        return response()->json($result, $status, $headers, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
    }
}

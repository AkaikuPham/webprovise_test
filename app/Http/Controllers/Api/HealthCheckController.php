<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;

class HealthCheckController extends BaseController
{
    /**
     * Check app health check connection.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // \DB::connection()->getPdo();

            return $this->response([
                'result' => 'ok'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            abort(Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}

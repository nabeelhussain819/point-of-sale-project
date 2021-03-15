<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\AppliesQueryParams;
use App\Traits\Paginates;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AppliesQueryParams, Paginates;

    public function __construct()
    {
    }
    public function genericResponse(bool $success, string $message = NULL, $statusCode = null, array ...$params)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        $response = array_merge(
            $response,
            collect($params)
                ->mapWithKeys(function ($a) {
                    return $a;
                })
                ->all()
        );

        return response($response, $this->getStatusCode($statusCode, $success) );
    }

    private function getStatusCode($statusCode, $success)
    {
        if (empty($statusCode)) {
            return $success ? 200 : 400;
        }
        return $statusCode;
    }
}

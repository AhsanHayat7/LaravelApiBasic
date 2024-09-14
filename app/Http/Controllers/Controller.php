<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // protected function SuccessResponse($data, $messages = "Success", $status = 200)
    // {
    //     return response()->json([
    //         "messages" => $messages,
    //         "data" => $data,
    //     ], $status);
    // }
}

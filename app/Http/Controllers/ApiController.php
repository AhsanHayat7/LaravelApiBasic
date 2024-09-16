<?php

namespace App\Http\Controllers;

class Apicontroller extends Controller
{

    protected function successResponse($data, $messages = "Success", $status = 200)
    {

        return response()->json([
            "messages" => $messages,
            "data" => $data,
        ], $status);
    }

    protected function errorResponse($messages = "Error", $status = 500 ){
        return response()->json([
            "messages" => $messages,
        ], $status);
    }
}

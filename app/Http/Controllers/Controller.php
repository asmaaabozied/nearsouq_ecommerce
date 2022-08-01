<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function responseJson($status, $message, $data = null)
    {

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
                    ];

        return response()->json($response);


    }

    function responseWithoutMessageJson($status,$data = null)
    {

        $response = [
            'status' => $status,
            'data' => $data
        ];

        return response()->json($response);


    }
    

}

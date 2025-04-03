<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function Response($message=null,$statusCode=null,$data=null)
    {
        if(empty($data))
        {
            return Response::json([

                'message' => $message,

                'statusCode' => $statusCode,    

            ], $statusCode);
            
        }else
        {
            return Response::json([

                'message' => $message,

                'data'    => []    
            ], $statusCode);
        }
    }
}

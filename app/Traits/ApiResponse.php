<?php
namespace App\Traits;
 
Trait ApiResponse
{
    public function onSuccess($status = "success", $data = [], $message = "", $response = 200)
    {
        $data = [
            "status" => $status,
            "data" => $data,
            "message" => $message
        ];
        return response()->json($data, $response);
    }
    
    public function onFailled($status = "failled", $message = "", $response = 400)
    {
        $data = [
            "status" => $status, 
            "message" => $message
        ];
        return response()->json($data, $response);
    }
    
}
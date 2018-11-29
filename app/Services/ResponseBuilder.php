<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 28.11.2018
 * Time: 21:05
 */

namespace App\Services;


use Illuminate\Support\Facades\Response;

trait ResponseBuilder
{
    private $jsonOptions = JSON_UNESCAPED_SLASHES+JSON_UNESCAPED_UNICODE;

    private $successResponse = ['status'=>'ok'];

    private $negativeResponse = ['status'=>'error',
        'description'=>null];

    /**
     * аргумент может быть любым типом
     * @param $data
     * @return array
     */
    private function getNegativeResponse($data):array
    {
        $response = $this->negativeResponse;
        $response['description']=$data;
        return $response;
    }

    /**
     * аргумент может быть любым типом
     * @param $data
     * @return array
     */
    private function getSuccessResponse($data):array
    {
        $response = $this->successResponse;
        $response['description']=$data;
        return $response;
    }

    protected function buildResponse(bool $mode,int $code,$data=null)
    {
        if ($mode===false){
            if ($data!==null) {
                return Response::json($this->getNegativeResponse($data), $code,[],$this->jsonOptions);
            }else{
                return Response::json($this->getNegativeResponse('unknown error'), $code,[],$this->jsonOptions);
            }
        }else{
            if($data!==null) {
                return Response::json($this->getSuccessResponse($data), $code, [], $this->jsonOptions);
            }else{
                return Response::json($this->successResponse, $code, [], $this->jsonOptions);
            }
        }
    }
}
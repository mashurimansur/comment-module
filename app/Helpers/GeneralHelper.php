<?php

namespace App\Helpers;

use Request;

class GeneralHelper
{
    private $request;

    public function __construct()
    {
        $this->request = app('Illuminate\Http\Request');
    }

    public $STAT_OK = 200;
    

    public $STAT_REQUIRED = 201;


    public $STAT_BAD_REQUEST = 400;
    

    public $STAT_UNAUTHORIZED = 401;
    

    public $STAT_NOT_FOUND = 404;
    

    public $STAT_REQUEST_TIMEOUT = 408;
    

    public $STAT_SERVICE_UNAVAILABLE = 503;
    
    //format_response
    public function HEADERS_REQUIRED($metode) {
        return [
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Methods" => $metode,
            "Access-Control-Allow-Headers" => "*",
            "Access-Control-Request-Headers" => "*"
        ];
    }

    public function STAT_OK() {
        return $this->STAT_OK;
    }

    public function STAT_REQUIRED() {
        return $this->STAT_REQUIRED;
    }

    public function STAT_BAD_REQUEST() {
        return $this->STAT_BAD_REQUEST;
    }

    public function STAT_UNAUTHORIZED() {
        return $this->STAT_UNAUTHORIZED; 
    }

    public function STAT_NOT_FOUND() {
        return $this->STAT_NOT_FOUND;
    }

    public function STAT_REQUEST_TIMEOUT() {
        return $this->STAT_REQUEST_TIMEOUT;
    }

    public function STAT_SERVICE_UNAVAILABLE() {
        return $this->STAT_SERVICE_UNAVAILABLE;
    }

    public function formatResponseWithPages($status,$data,$code = 200, $page = null)
    {
        //sesuaikan dengan aturan keluaran yang diinginkan, yang dibawah contoh makassar app
        $response = "";
        
        if ($this->request->is('eproc/api/*')){
            $dgn = [
                'code'  => $code,
                'status' => $status,
            ];
        } else {
            if ($this->request->user() == "") {
                $login = false;
            } else {
                $login = true;
            }

            if($this->request->headers->get("User-Id") != 0){
                $login = true;
            }

            $dgn = [
                'code'  => $code,
                'status' => $status,
                'login' => $login
            ];
        }
        if ($code == 200) {
            //success
            if ($page == null) {
                $response = [
                    'diagnostics' => $dgn,
                    'response' => $data
                ];
            } else {
                $response = [
                    'diagnostics' => $dgn,
                    'pagination' => $page,
                    'response' => $data
                ];
            }
        } else if ($code == 201) {
            $response = [
                'diagnostics' => $dgn,
                'response' => $data
            ];
        } else {
            $response = [
                'diagnostics' => $dgn
            ];
        }
        return $response;
    }

    public function stringToObjectId($id)
    {
            $objId = new \MongoDB\BSON\ObjectId($id);
            return $objId;
    } 
}
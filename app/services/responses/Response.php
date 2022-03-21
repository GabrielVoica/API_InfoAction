<?php
class Response {

    public static function successful(){
        return array("code" => "200", "message" => "Request was successfuly delivered");
    }

    public static function successfulData($data=null){
        return array("code" => "200", "message" => "Request was successfuly delivered","data" => $data);
    }

}
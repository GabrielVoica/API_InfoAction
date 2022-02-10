<?php
class Response {

    public static function successful(){
        return array("code" => "200", "message" => "Request was successful delivered");
    }

}
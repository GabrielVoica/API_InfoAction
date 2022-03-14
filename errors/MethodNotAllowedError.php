<?php

class MethodNotAllowedError implements HttpError{
    public static function throw(){
        return array('code'=> 405,'message'=> 'The request method is not allowed');
    }
}
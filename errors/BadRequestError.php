<?php


class BadRequestError implements HttpError{

    public static function throw(){
        return array('code' => 400,'message' => 'The request values are invalid');
    }
}

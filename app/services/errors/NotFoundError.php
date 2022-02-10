<?php

class NotFoundError implements HttpError{
    public static function throw(){
        return array('code'=> 404,'message'=> 'Resource not found');
    }
}
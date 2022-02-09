<?php

class ForbiddenError implements HttpError{
    public static function throw(){
        return array('code'=> 403,'message'=> 'You dont have permission to access / on this page');
    }
}
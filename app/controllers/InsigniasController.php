<?php

require_once('services/responses/Response.php');
    
// Happy coding :)
    
class InsigniasController implements Controller 
{
    public function __construct(){

        
    }


    public function get($params){
        return 'get';
    }

        
    public function post($variables){
        return 'post';
    }


    public function put($variables){
        return 'get';
    }


    public function delete($variables){
        return 'delete';
    }
        
} 
        
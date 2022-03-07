<?php


require_once('src/models/Session.php');
require_once('services/errors/NotFoundError.php');
require_once('services/responses/Response.php');
require_once("services/Database.php");

class SessionController implements Controller
{

    public function get($variables)
    {
        $result = Session::getField($variables);


        $response = Response::successful();
        $response ['message'] = $result['message'];
    
    
        if ($result == true) {
          return $response;
        } else {
          return false;
        }
    }

    public function post($variables)
    {
    
    }

    public function put($variables)
    {
        
    }

    public function delete($variables)
    {
    }
}

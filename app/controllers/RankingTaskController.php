<?php


require_once('src/models/Badge.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");
require_once("services/cookies/CookieService.php");

    
// Happy coding :)
    
class RankingTaskController implements Controller 
{
    public function __construct(){

        
    }


    public function get($params){
        if(count($params) > 1){
            $result = Badge::get($params[1]);
      
          }
          else{
            $result = Badge::getAll();
          }
      
      
           if ($result['result'] == true) {
            $response['code'] = Response::successful()['code'];
            $response['message'] = Response::successful()['message'];
            $response ['data'] = $result['data'];
            return $response;
          } else {
            $response['code'] = NotFoundError::throw()['code'];
            $response['message'] = NotFoundError::throw()['message'];
            return $response;
          }
    }

        
    public function post($variables){
        $result = Badge::insert($variables);
    
        if ($result['result'] == true) {
          $response = Response::successful();
          $response ['message'] = Response::successful()['message'];
          return $response;
        } else {
          $response = BadRequestError::throw();
          $response ['message'] = $result['message'];
          return $response;
        }    }


    public function put($variables){
        $result = Badge::update($variables);

        if ($result['result'] == true) {
          $response['code'] = Response::successful()['code'];
          $response['message'] = Response::successful()['message'];
          return $response;
        } else {
          $response = BadRequestError::throw();
          $response['message'] = $result['message'];
          return $response;
        }
    }


    public function delete($variables){
        $result = Badge::delete($variables, 'badge');

    if ($result['result'] == true) {
      $response['code'] = Response::successful()['code'];
      $response['message'] = Response::successful()['message'];
      return $response;
    } else {
      $response['code'] = NotFoundError::throw()['code'];
      $response['message'] = $result['message'];
      return $response;
    }
    }
        
} 
        
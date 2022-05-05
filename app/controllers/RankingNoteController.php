<?php

require_once('src/models/RankingNote.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");

class RankingNoteController implements Controller
{

    private $connection;
    private $badRequestError;
    private $database;

    
    public function __construct($database)
    {
      $database->connect();
      $this->connection = $database->getConnection();
      $this->badRequestError = new BadRequestError();
      $this->database = new Database();
      $this->response = new Response();
    }

    public function get($params)

    {

      if(count($params) < 2){
        return BadRequestError::throw();
      }

      $paramsinput['code'] = $params[1];
      if(count($params) > 2){
      $paramsinput['id'] = $params[2];
      }

      
      if(count($params) > 2){
        $result = RankingNote::get($paramsinput);
      }
      else{
        $result = RankingNote::getAll($paramsinput);
      }
  
  
       if ($result['result'] == true) {
        $response['code'] = Response::successful()['code'];
        $response['message'] = Response::successful()['message'];
        $response ['data'] = $result['data'];
        return $response;
      } else {
        $response['code'] = NotFoundError::throw()['code'];
        $response['message'] = $result['message'];
        return $response;
      }
    }

    public function post($variables)
    {
      $result = RankingNote::insert($variables);
  
      if ($result['result'] == true) {
        $response = Response::successful();
        $response ['message'] = Response::successful()['message'];
        return $response;
      } else {
        $response = BadRequestError::throw();
        $response ['message'] = $result['message'];
        return $response;
      }

  }

    public function put($variables)
    {
      $result = RankingNote::update($variables);

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

    public function delete($variables)
    {

      $paramsinput['code'] = $variables[1];
      $paramsinput['id'] = $variables[2];

      
      $result = RankingNote::delete($paramsinput,'user');
  
      $response = Response::successful();
      $response ['message'] = $result['message'];
  
  
      if ($result == true) {
        return $response;
      } else {
        return false;
      }
    }
}

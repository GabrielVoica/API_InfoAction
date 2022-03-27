<?php

require_once('src/models/RankingData.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");

class RankingDataController implements Controller
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
      if(count($params) > 1){
        $result = RankingData::get($params[1]);
  
      }
      else{
        $result = RankingData::getAll();
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

    public function post($variables)
    {
      $this->database->connect();
    $result = RankingData::insert($variables);


    $response = Response::successful();
    $response ['message'] = $result['message'];


    if ($result == true) {
      return $response;
    } else {
      return false;
    }


    }

    public function put($variables)
    {
      $result = RankingData::update($variables);

      $response = Response::successful();
      $response ['message'] = $result['message'];
  
  
      if ($result == true) {
        return $response;
      } else {
        return false;
      }
        
    }

    public function delete($variables)
    {
      $this->database->connect();
      $result = RankingData::delete($variables,'rankingdata');
  
      $response = Response::successful();
      $response ['message'] = $result['message'];
  
  
      if ($result == true) {
        return $response;
      } else {
        return false;
      }
    }
}

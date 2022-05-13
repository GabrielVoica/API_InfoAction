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
      $result = RankingData::insert($variables);
  
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
      $result = RankingData::update($variables);


  
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

    public function delete($variables)
    {
      $result = RankingData::delete($variables,'rankingdata');
  
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

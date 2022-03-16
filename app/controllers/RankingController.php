<?php

require_once('src/models/Ranking.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");

class RankingController implements Controller
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
       return 'Welcome to our api!'; 
    }

    public function post($variables)
    {
      $this->database->connect();
      $result = Ranking::insert($variables);
  
  
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
        
    }

    public function delete($variables)
    {
      $this->database->connect();
      $result = Ranking::delete($variables,'user');
  
      $response = Response::successful();
      $response ['message'] = $result['message'];
  
  
      if ($result == true) {
        return $response;
      } else {
        return false;
      }
    }
}

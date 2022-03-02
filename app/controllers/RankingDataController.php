<?php

require_once('src/models/Ranking.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");

class HomeController implements Controller
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
    
    }

    public function put()
    {
        
    }

    public function delete()
    {
    }
}

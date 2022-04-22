<?php

class RankingTask implements Controller
{

  /**
   * Use this object to make CRUD operations to the database
   */
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
  }

  public function post($variables)
  {
    $this->database->connect();

  }

  public function put($variables)
  {
  }

  public function delete($variables)
  {
  }
}

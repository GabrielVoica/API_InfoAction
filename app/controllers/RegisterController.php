<?php

require_once('src/models/User.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");


class RegisterController implements Controller
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
    $result = User::insert($variables);


    $response = Response::successful();
    $response ['final'] = $result['final'];


    if ($result == true) {
      return $response;
    } else {
      return false;
    }
  }

  public function put()
  {
  }

  public function delete()
  {
  }
}

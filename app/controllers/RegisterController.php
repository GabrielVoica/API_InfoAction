<?php

require_once('src/models/User.php');
require "services/errors/BadRequestError.php";


class RegisterController implements Controller
{

  /**
   * Use this object to make CRUD operations to the database
   */
  private $connection;
  private $badRequestError;



  public function __construct($database)
  {
    $database->connect();
    $this->connection = $database->getConnection();
    $this->badRequestError = new BadRequestError();



  }



  public function get($params)
  {

  }

  public function post($variables)
  { 

    if (!isset($variables["name"])) {
      return $this->badRequestError::throw();
    }


    return User::insert($variables);
  }

  public function put()
  {
  }

  public function delete()
  {
  }
}

<?php


require "src/interface/Controller.php";

class RegisterController implements Controller
{

  /**
   * Use this object to make CRUD operations to the database
   */
  private $connection;



  public function __construct($database)
  {
    $database->connect();
    $this->connection = $database->getConnection();
  }



  public function get()
  {
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

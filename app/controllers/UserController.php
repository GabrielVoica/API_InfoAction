<?php


require_once('src/models/User.php');

class UserController implements Controller
{

  /**
   * Use this object to make CRUD operations to the database
   */
  private $connection;

  private $userModel;



  public function __construct($database)
  {
    $database->connect();
    $this->connection = $database->getConnection();
    $this->userModel = new User();
  }

  public function get($params)
  {
    return User::get($params[1],null);


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

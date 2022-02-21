<?php

require_once('src/models/User.php');
require_once('services/errors/NotFoundError.php');
require_once('services/responses/Response.php');

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

    $user = User::get($params[1],null);

    if($user == null){
      return NotFoundError::throw();
    }
    else{
      return Response::successfulData($user);
    }
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

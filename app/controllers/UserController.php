<?php

require_once('src/models/User.php');
require_once('services/errors/NotFoundError.php');
require_once('services/responses/Response.php');
require_once("services/Database.php");


class UserController implements Controller
{

  /**
   * Use this object to make CRUD operations to the database
   */
  private $connection;
  private $database;
  private $userModel;


  public function __construct($database)
  {
    $database->connect();
    $this->database = new Database();

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


  public function put($variables)
  {
  }



  public function delete($variables)
  {


    $this->database->connect();
    $result = User::delete($variables,'user');

    $response = Response::successful();
    $response ['message'] = $result['message'];


    if ($result == true) {
      return $response;
    } else {
      return false;
    }
  }
}

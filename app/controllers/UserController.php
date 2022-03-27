<?php

require_once('src/models/User.php');
require_once('services/errors/NotFoundError.php');
require_once('services/errors/BadRequestError.php');

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



    if(count($params) > 1){
      $result = User::get($params[1]);

    }
    else{
      $result = User::getAll();
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
  }


  public function put($variables)
  {
    $result = User::update($variables);

    if ($result['result'] == true) {
      $response['code'] = Response::successful()['code'];
      $response['message'] = Response::successful()['message'];
      return $response;
    } else {
      $response = BadRequestError::throw();
      $response['message'] = $result['message'];
      return $response;
    }
  }



  public function delete($variables)
  {

    $result = User::delete($variables, 'user');

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

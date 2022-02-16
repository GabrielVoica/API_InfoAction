<?php

require_once('src/models/User.php');
require_once "services/errors/BadRequestError.php";
require_once "services/Database.php";


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




  }



  public function get($params)
  {

  }

  public function post($variables)
  { 
    $this->database->connect();

    //TODO implements in model
    $columns_show = "SHOW COLUMNS FROM user";
    $types = $this->database->getConnection()->query($columns_show);
    $types = mysqli_fetch_all($types);
    $keys = array_keys($variables);




    for($y = 0; $y < count($types); $y++){
      for($x = 0; $x < count($keys); $x++){     
      if(array_diff($keys,$types[$y][0]) ){
        return BadRequestError::throw();
      }
    } 
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

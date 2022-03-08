<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


require_once("src/lib/Insert.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Update.php");
require_once("src/lib/Validator.php");




class User implements Model
{

    public static function get($id = null, array $fields = null)
    {

        if ($fields === null) {
            $query = "SELECT * FROM user WHERE id = $id";
        } else {
            $query = "SELECT * FROM user WHERE ";
            $keys = array_keys($fields);
            $values = array_values($fields);
            for ($i = 0; $i < count($fields); $i++) {
                if (count($fields) === 1) {
                    $query = $query . "$keys[$i] = '$values[$i]'";
                } else {
                    $query = $query . "$keys[$i] = '$values[$i]' AND ";
                    if ($i == count($fields) - 1) {
                        $query = substr($query, 0, strlen($query) - 5);
                    }
                }
            }
        }

        $database = new Database();

        $database->connect();

        $data = $database->getConnection()->query($query);

        if ($data === false) {
            return false;
        }

        if (mysqli_num_rows($data) == 0) {
            return false;
        }

        $data = mysqli_fetch_assoc($data);




        return $data;
    }



    public static function getField($id, $field)
    {
        $query = "SELECT $field FROM user WHERE id = $id";

        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        return $data[$field];
    }



    public static function login($email, $password)
    {
        $query = "SELECT * FROM user WHERE email = '$email'";


        $database = new Database();

        $database->connect();

        $data = $database->getConnection()->query($query);

        $data = mysqli_fetch_assoc($data);


        if (isset($data['password']) && password_verify($password, $data['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAll()
    {
        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query('SELECT * FROM user');
        $data = mysqli_fetch_assoc($data);

        return $data;
    }


    public static function insert(array $fields = null)
    {

        
        $columnsRequired = Insert::showRequiredColumns('user');
        $columnsAll = Insert::showColumnsWithoutID('user');

        $fieldsKeys = array_keys($fields);

        

        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }


        if(isset($fields['nick_name'])){
             //Works
            if (!Validator::isText($fields['nick_name'])) {
                return array('result' => false, 'message' => 'Nickname must include only letters');
            }
            
            //Works
            $LenghtReturn = Validator::isLenght($fields['nick_name'],'user','nick_name',5,null);
            if($LenghtReturn > 1){
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
    
            //Works
            if(!Validator::isExist('user','nick_name',$fields['nick_name'])){
                return array('result' => false, 'message' => ''.$fields['nick_name'].' exist, use another');
            }

        }


        if(isset($fields['email'])){
            //Works
            if(!Validator::isEmail($fields['email'])) {
                return array('result' => false, 'message' => 'Email is not correct');
            } 
    
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['email'],'user','email',10,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
    
            //Works
            if(!Validator::isExist('user','email',$fields['email'])){
                return array('result' => false, 'message' => ''.$fields['email'].' exist, use another');
            }
        }

    
        if(isset($fields['password'])){


            //Works
            $nameLenghtReturn = Validator::isLenght($fields['password'],'user','password',8,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            $PasswordCheck = Validator::isPassword($fields['password']);
            if ($PasswordCheck > 1) {
                return array('result' => false, 'message' => $PasswordCheck['message']);
            } else {
                $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
            
            }
        }



        if(isset($fields['name'])){
            //Works
            if(!Validator::isText($fields['name'])){
                return array('result' => false, 'message' => 'Name must include only letters');

            }

           //Works
            $nameLenghtReturn = Validator::isLenght($fields['name'],'user','name',5,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }

      

        if(isset($fields['lastname'])){
            //Works
            if(!Validator::isText($fields['lastname'])){
                return array('result' => false, 'message' => 'Lastname must include only letters');

            }
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['lastname'],'user','lastname',5,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if(isset($fields['center_id'])){
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['center_id'],'user','center_id',1,5);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if($fields['center_id'] < 0){
                return array('result' => false, 'message' => 'No negative numbers');

            }
            //Works
            if(!Validator::isNumber($fields['center_id'])){
                return array('result' => false, 'message' => 'Center ID : Only numbers');

            }
        
        }

        if(isset($fields['rol'])){
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['rol'],'user','rol',1,1);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if(!Validator::isNumber($fields['rol'])){
                return array('result' => false, 'message' => 'Rol : Only numbers');
            }

            //Works
            if($fields['rol'] != 0 & $fields['rol'] != 1){
                return array('result' => false, 'message' => 'Rol : Only 0 => Student or 1 => Teacher');

            }
          
        }


        $database = new Database();
        $database->connect();

        $types = Insert::showColumns('user');

        $query = Insert::makeInsertQuery('user', $fields, $types);


        $data = $database->getConnection()->query($query);

        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }


    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();

        $queryidexist = Delete::existID($fields);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        
        $querydelete = Delete::deleteRow($fields);
        $data = $database->getConnection()->query($querydelete);



        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }

    }



    public static function update(array $fields = null)
    {
        $database = new Database();
        $database->connect();



        if(!isset($fields['id'])){
            return array('result' => false, 'message' =>  'ID field not exist');

        }

        $queryidexist = Update::existID('user',$fields['id']);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        $types = Insert::showColumns('user');
        $querydelete = Update::updateRow('user',$fields,$types);
        $data = $database->getConnection()->query($querydelete);



        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

<?php

require_once("services/Database.php");
require_once("services/Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("services/Insert.php");


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
            if (!Validator::isText($fields['nick_name'])) {
                return array('result' => false, 'message' => 'The nickname have only letters');
            }
            
            $nameLenghtReturn = Validator::isLenght($fields['nick_name'],'user','nick_name',5);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
    
            if(!Validator::isExist('user','nick_name',$fields['nick_name'])){
                return array('result' => false, 'message' => ''.$fields['nick_name'].' exist, use another');
            }

        }

        if(isset($fields['email'])){
            if(!Validator::isEmail($fields['email'])) {
                return array('result' => false, 'message' => 'Email for is not correct');
            } 
    
            $nameLenghtReturn = Validator::isLenght($fields['email'],'user','email',10);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
    
            if(!Validator::isExist('user','email',$fields['email'])){
                return array('result' => false, 'message' => ''.$fields['email'].' exist, use another');
            }
        }

    
        if(isset($fields['password'])){
            $PasswordCheck = Validator::isPassword($fields['password']);
            if (!Validator::isPassword($fields['password'])) {
                return array('result' => false, 'message' => $PasswordCheck);
            } else {
                $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
            }
        }

        if(isset($fields['name'])){
            if(!Validator::isText($fields['name'])){
                return array('result' => false, 'message' => 'The name have only letters');

            }
            $nameLenghtReturn = Validator::isLenght($fields['name'],'user','name',5);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }

      

        if(isset($fields['lastname'])){
            if(!Validator::isText($fields['lastname'])){
                return array('result' => false, 'message' => 'The lastname have only letters');

            }
            $nameLenghtReturn = Validator::isLenght($fields['lastname'],'user','lastname',5);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if(isset($fields['center_id'])){
            if(!Validator::isNumber($fields['center_id'])){
                return array('result' => false, 'message' => 'Center ID : Only numbers');

            }
        }

        if(isset($fields['rol'])){
            if(!Validator::isNumber('rol')){
                return array('result' => false, 'message' => 'Rol : Only numbers');
            }
            $nameLenghtReturn = Validator::isLenght($fields['lastname'],'user','lastname',5);

            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }
        $database = new Database();
        $database->connect();

        $types = Insert::showColumns('user');
        $query = Insert::makInsertQuery('user', $fields, $types);

        $data = $database->getConnection()->query($query);

        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }


    public static function delete($id)
    {
    }

    public static function deleteAll()
    {
    }

    public static function update($id, array $fields)
    {
    }
}

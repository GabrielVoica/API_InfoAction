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

        
        $columns = Insert::showRequiredColumns('user');
        $fieldsKeys = array_keys($fields);

        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columns);
        if($CheckFieldsInsert > 1){
            $FinalCheck['message'] = $CheckFieldsInsert['message'];
            return array('result' => false, 'message' => 'Missing field '.$FinalCheck['message'].'');

        }




        /*if(Validator::isNumber($fields['nick_name'])){
            return array('result' => false, 'final' => 'Only letters and numbers');
        }*/

        
        $nameLenghtReturn = Validator::isLenght($fields['nick_name'],'user','nick_name',5);
        if($nameLenghtReturn > 1){
            return array('result' => false, 'message' => $nameLenghtReturn['message']);
        }




        if(!Validator::isEmail($fields['email'])) {
            return array('result' => false, 'message' => 'Email for is not correct');
        } 

        /*$nameLenghtReturn = Validator::isLenght($fields['email'],'user','email',5);
        if($nameLenghtReturn > 1){
            $FinalCheck['message'] = Validator::isLenght($fields['email'],'user','email',5);
            return array('result' => false, 'message' => 'Length must be 15-35');
        }*/


        /*if (!Validator::isPassword($fields['password'])) {
            return array('result' => false, 'final' => $response);
        } else {
            $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
        }



        if (!isset($fields['name']) || !Validator::isName($fields['name'],5,15)) {
            return false;
        }
        
        /*if (!isset($fields['lastname']) || !Validator::isName($fields['lastname'],5,15)) {
            return false;
        }


        /*if (!isset($fields['center_id']) || !Validator::isNumber($fields['center_id'])) {
            return false;
        }*/


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

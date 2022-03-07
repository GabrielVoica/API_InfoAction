<?php

require_once("services/Database.php");
require_once("services/Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("services/Insert.php");
require_once("services/Create.php");



class RankingData implements Model
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



        $columnsRequired = Insert::showRequiredColumns('rankingdata');
        $columnsAll = Insert::showColumnsWithoutID('rankingdata');

        $fieldsKeys = array_keys($fields);


        if($fields['creationdate'] == null){
            $fields['creationdate'] = "CURRENT_TIMESTAMP";
        }


        

        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }


        /*if(isset($fields['ranking_name'])){
            //Works
           if (!Validator::isText($fields['ranking_name'])) {
               return array('result' => false, 'message' => 'Ranking Name must include only letters');
           }
           
           //Works
           $LenghtReturn = Validator::isLenght($fields['ranking_name'],'rankingdata','ranking_name',4,null);
           if($LenghtReturn > 1){
               return array('result' => false, 'message' => $LenghtReturn['message']);
           }
   
           //Works
           if(!Validator::isExist('rankingdata','ranking_name',$fields['ranking_name'])){
               return array('result' => false, 'message' => ''.$fields['ranking_name'].' exist, use another');
           }

       }




       if(isset($fields['teacher_id'])){
        if(!Validator::isNumber($fields['teacher_id'])){
            return array('result' => false, 'message' => 'Teacher_ID : Only numbers');

        }
       }*/


        $database = new Database();
        $database->connect();

        $types = Insert::showColumns('rankingdata');
        $query = Insert::makeInsertQuery('rankingdata', $fields, $types);

        $rankingstructure = array(
            "id" => "int PRIMARY KEY",
            "user_name" => "varchar(20)",
            "nameandlastname" => "varchar(40)",
            "points" => "int"
        );


        $querycreate = Create::makeCreateQuery($fields['ranking_name'],$rankingstructure);


        $data = $database->getConnection()->query($query);
        if ($data) {
            $querycreate = Create::makeCreateQuery($fields['ranking_name'],$rankingstructure);
            $data = $database->getConnection()->query($querycreate);

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

    public static function update(array $fields = null)
    {
    }
}

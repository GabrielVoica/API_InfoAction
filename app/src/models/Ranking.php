<?php

require_once("services/Database.php");
require_once("src/lib//Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Get.php");



class Ranking implements Model
{

    public static function get($id = null, array $fields = null)
    {

    }



    public static function getField($id, $field)
    {
      
    }
  

    public static function getAll()
    {
     
    }


    public static function insert(array $fields = null)
    {
        $database = new Database();
        $database->connect();



        if(!isset($fields['ranking_id'])){
            return array('result' => false, 'message' => 'Missing ID Ranking');
        }
        else{
            if(Validator::isText($fields['ranking_id'])){
                return array('result' => false, 'message' => 'Ranking ID must be only numbers');
            }
            if(Validator::isExist('rankingdata','id',$fields['ranking_id'])){
                return array('result' => false, 'message' => ''.$fields['ranking_id'].' not exist, use another');
            }
        }


        
        if(!isset($fields['user_id'])){
            return array('result' => false, 'message' => 'Missing ID User');
        }
        else{
            if(Validator::isText($fields['user_id'])){
                return array('result' => false, 'message' => 'User ID must be only numbers');
            }
            if(Validator::isExist('user','id',$fields['user_id'])){
                return array('result' => false, 'message' => ''.$fields['user_id'].' not exist, use another');
            }
        }




        $query = GET::getTable('rankingdata',$fields['ranking_id'],'id');
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
        $ranking_name = "R_".$data['ranking_name'];


        $query = GET::getTable('user',$fields['user_id'],'id');
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);


        $fields['nick_name'] = $data['nick_name'];
        $fields['name_lastname'] = $data['name'].' '.$data['lastname'];
        $fields['points'] = 0;
        $fields['status'] = 0;
        $fields['level'] = 0;




        $columnsRequired = Insert::showRequiredColumns($ranking_name);
        $columnsAll = Insert::showColumnsWithoutID($ranking_name);


        unset($fields['ranking_id']);
        unset($fields['user_id']);


        $fieldsKeys = array_keys($fields);
        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }

       ;

        

        $types = Insert::showColumns($ranking_name);

        $query = Insert::makeInsertQuery($ranking_name, $fields, $types);
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

    public static function update(array $fields = null)
    {
    }
}

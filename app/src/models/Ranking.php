<?php

require_once("services/Database.php");
require_once("src/lib//Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Get.php");
require_once("src/lib/Delete.php");




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
        //Create Database Connection
        $database = new Database();
        $database->connect();


        //Post: Ranking id Validators
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


        //POST: User id validators
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


        //With ranking id, get ranking name and save ranking name in variable
        $query = GET::getTable('rankingdata',$fields['ranking_id'],'id');
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
        $ranking_name = "R_".$data['ranking_name'];


        //With user id, get user data and save in fields to make inserts
        $query = GET::getTable('user',$fields['user_id'],'id');
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);


        //Fields to make insert in Ranking Rows
        $fields['id'] = $data['id'];
        $fields['nick_name'] = $data['nick_name'];
        $fields['name_lastname'] = $data['name'].' '.$data['lastname'];
        $fields['points'] = 0;
        $fields['status'] = 0;
        $fields['level'] = 0;

        //Save ranking id and user id for save in ranking members, all rows
        $rankingmembers['id_ranking'] = $fields['ranking_id'];
        $rankingmembers['id_user'] = $fields['id'];

        //If ID user exist in table user, later user exist in ranking table
        if(isset($fields['nick_name'])){
            if(!Validator::isExist($ranking_name,'nick_name',$fields['nick_name'])){
                return array('result' => false, 'message' => ''.$fields['nick_name'].' exist, use another');
            }        
        }
     
        //Select required columns for ranking
        $columnsRequired = Insert::showRequiredColumns($ranking_name);
        $columnsAll = Insert::showColumns($ranking_name);

        //Delete ranking_id, user_id for make insert
        unset($fields['ranking_id']);
        unset($fields['user_id']);

        //Function missing NOT NULL field
        $fieldsKeys = array_keys($fields);
        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }

        //Select rankingname, put insert fields and fields ranking
        $types = Insert::showColumns($ranking_name);
        $query = Insert::makeInsertQuery($ranking_name, $fields, $types);
        $data = $database->getConnection()->query($query);

        if ($data) {
        //IF query is correct, insert in raking members ranking_id and user_id    
        $ranking_name = 'rankingmembers';    
        $types = Insert::showColumns($ranking_name);
        $query = Insert::makeInsertQuery($ranking_name, $rankingmembers, $types);
        $data = $database->getConnection()->query($query);

            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }

    }
       
        

    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();

        $fields = $fields[1];
        $fields = explode('-',$fields);


        $tableExist = Delete::ExistTable($fields[0]);
        if($tableExist > 1){
            return array('result' => false, 'message' =>  $tableExist['message']);

        }


        $queryidexist = Delete::existID($fields);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        
        if(count($fields) == 1){
            $querydelete = Delete::deleteRow($fields[0],null);
        }
        else{
            $querydelete = Delete::deleteRow($fields[0],$fields[1]);

        }

        $data = $database->getConnection()->query($querydelete);

        if ($data) {
            $fields[0] = 'rankingmembers';
            $querydelete = Delete::deleteRowWithField($fields[0],$fields[1],'id_user');
            $data = $database->getConnection()->query($querydelete);

            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }

    public static function deleteAll()
    {
    }

    public static function update(array $fields = null)
    {
    }
}

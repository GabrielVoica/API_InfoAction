<?php

require_once("services/Database.php");
require_once("services/Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("services/Insert.php");


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



        $columnsRequired = Insert::showRequiredColumns($fields['ranking_name']);
        $columnsAll = Insert::showColumnsWithoutID($fields['ranking_name']);

        $ranking_name = $fields['ranking_name'];
        unset($fields['ranking_name']);


        $fieldsKeys = array_keys($fields);


        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }


        $database = new Database();
        $database->connect();

        $types = Insert::showColumns($ranking_name);

        $query = Insert::makeInsertQuery($ranking_name, $fields, $types);

        print_r($query);

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

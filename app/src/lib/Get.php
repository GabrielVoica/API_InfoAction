<?php

require_once("services/Database.php");


class Get
{

    public static function getTable($table,$id,$id_field,$fields)
    {
        $database = new Database();
        $database->connect();

       $query = "SELECT * FROM $table WHERE $id_field = $id";


        return $query;
    }


    public static function getDataField($table,$value,$fields){
        $database = new Database();
        $database->connect();

       $query = "SELECT * FROM $table WHERE $fields = $value";


        return $query;
    }

  

}   
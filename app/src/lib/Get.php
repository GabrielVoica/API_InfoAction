<?php

require_once("services/Database.php");


class Get
{

    //Function get table with ID
    public static function getTable($table,$id,$id_field)
    {
        $database = new Database();
        $database->connect();

       $query = "SELECT * FROM $table WHERE $id_field = $id";


        return $query;
    }

    //Function get table with varchar field
    //TODO make one function, get show columns, and if is varchar or int put quotes or no
    public static function getTableVarchar($table,$id,$id_field){
        $database = new Database();
        $database->connect();

       $query = "SELECT * FROM $table WHERE $id_field = '$id'";


        return $query;
    }


    //Function get with specific field
    public static function getDataField($table,$value,$fields){
        $database = new Database();
        $database->connect();

       $query = "SELECT * FROM $table WHERE $fields = $value";


        return $query;
    }

  

}   
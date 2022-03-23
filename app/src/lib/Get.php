<?php

require_once("services/Database.php");


class Get
{

    //Function get with specific field
    public static function getDataField($table, $value, $fields)
    {
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table WHERE $fields = $value";


        return $query;
    }

    public static function getAllData($table){
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table";
        return $query;
        
    }
}

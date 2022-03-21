<?php

require_once("Database.php");

class Create
{

    public static function createTable($tablename){

    }

    public static function makeCreateQuery($tablename,$tablestructure){
        
            $tablename = "R_".$tablename;
            $database = new Database();
            $database->connect();
            $querycreate = "CREATE TABLE $tablename (";
        

            $keys = array_keys($tablestructure);
            $values = array_values($tablestructure);


            for ($i = 0; $i < count($keys); $i++) {
                $querycreate = "$querycreate $keys[$i] $values[$i],";
            }
            $querycreate = substr($querycreate, 0, -1);

            $querycreate ="$querycreate)";
    
            return $querycreate;
    }
}
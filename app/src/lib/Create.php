<?php

require_once("services/Database.php");

class Create
{

    public static function createTable($tablename){}


    public static function makeCreateQuery($tablename,$tablestructure){
        
            
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
      


    public static function createEventUpdadePoints($table){
        $database = new Database();
        $database->connect();

        $querycreate = "CREATE EVENT updatePoints_$table ON SCHEDULE EVERY 1 WEEK STARTS '2022-02-28 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE $table set points = 1000";

        return $querycreate;
    }

}
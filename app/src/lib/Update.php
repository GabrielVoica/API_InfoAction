<?php

require_once("services/Database.php");

class Update
{

    //TODO optimize this function
    public static function updateRow($table, $fields,$wherefield, $wherevalue)
    {
        $database = new Database();
        $database->connect();

        $queryupdate = "UPDATE $table SET ";

    
        $keys = array_keys($fields);
        $values = array_values($fields);


        for ($y = 0; $y < count($fields); $y++) {
            $queryupdate = "$queryupdate$keys[$y]=$values[$y],";
        }

        $queryupdate = substr($queryupdate, 0, -1);

        $queryupdate = "$queryupdate WHERE $wherefield = $wherevalue";


        return $queryupdate;
    }
}

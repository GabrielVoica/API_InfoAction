<?php

require_once("services/Database.php");

class Update
{

    //TODO optimize this function
    public static function updateRow($table, $fields)
    {
        $database = new Database();
        $database->connect();

        $id = $fields['id'];
        unset($fields['id']);



        $queryupdate = "UPDATE $table SET ";

      

        $keys = array_keys($fields);
        $values = array_values($fields);


        for ($y = 0; $y < count($fields); $y++) {
            $queryupdate = "$queryupdate$keys[$y]=$values[$y],";
        }

        $queryupdate = substr($queryupdate, 0, -1);

        $queryupdate = "$queryupdate WHERE id = $id";


        return $queryupdate;
    }
}

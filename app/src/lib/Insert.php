<?php

require_once("services/Database.php");


class Insert
{


    //Function create insert query structure
    public static function makeInsertQuery($table, $fields, $values)
    {     
        $keys = array_keys($fields);


        $query = "INSERT INTO $table (";

        if (isset($values['password'])) {
            $values['password'] = $values['password'];
        }


        for ($i = 0; $i < count($values); $i++) {
            $query = "$query$keys[$i],";
        }

        $query = substr($query, 0, -1);
        $query = $query . ') ';
        $query = $query . "Values(";


        for ($i = 0; $i < count($values); $i++) {
            $query = "$query$values[$i],";
        }

        $query = substr($query, 0, -1);
        $query = $query . ");";


        return $query;
    }
}

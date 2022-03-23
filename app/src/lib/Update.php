<?php

require_once("services/Database.php");

class Update
{

    //TODO optimize this function
    public static function updateRow($table, $fields, $types)
    {
        $database = new Database();
        $database->connect();

        $id = $fields['id'];
        unset($fields['id']);

        unset($types[0]);


        $queryupdate = "UPDATE $table SET ";

        if (isset($fields['password'])) {
            $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
        }

        $keys = array_keys($fields);
        $values = array_values($types);


        for ($y = 0; $y < count($fields); $y++) {
            $queryupdate = "$queryupdate$keys[$y]=$values[$y],";
        }

        $queryupdate = substr($queryupdate, 0, -1);

        $queryupdate = "$queryupdate WHERE id = $id";


        return $queryupdate;
    }
}

<?php

require_once("Database.php");


class Insert
{

    public static function showColumns($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);

        return $types;
    }


    public static function showRequiredColumns($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table WHERE `Null` = 'No' AND field NOT LIKE 'id'";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);

        $columns = array_map('map', $types);

        return $columns;
    }




    public static function makInsertQuery($table, $fields, $types)
    {

        $query = "INSERT INTO $table (";

        if (isset($fields['password'])) {
            $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
        }

        $keys = array_keys($fields);
        $values = array_values($fields);

        for ($i = 0; $i < count($fields); $i++) {
            $query = "$query$keys[$i],";
        }

        $query = substr($query, 0, -1);
        $query = $query . ') ';
        $query = $query . "Values(";


        //TODO funcion aparte

        for ($x = 0; $x < count($types); $x++) {
            for ($y = 0; $y < count($fields); $y++) {
                if ($keys[$y] == $types[$x][0] && !str_contains($types[$x][1], 'int')) {
                    $values[$y] = "'$values[$y]'";
                }
            }
        }


        for ($i = 0; $i < count($fields); $i++) {
            $query = "$query$values[$i],";
        }

        $query = substr($query, 0, -1);
        $query = $query . ");";

        return $query;
    }
}




function map($data)
{
    return $data[0];
}

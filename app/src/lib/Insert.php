<?php

require_once("services/Database.php");


class Insert
{


    //Function create insert query structure
    public static function makeInsertQuery($table, $inputFields, $columnsTable)
    {

        $query = "INSERT INTO $table (";

        if (isset($fields['password'])) {
            $fields['password'] = $fields['password'];
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
                if ($keys[$y] == $types[$x][0] && !str_contains($types[$x][1], 'int') && !str_contains($values[$y], 'CURRENT_TIMESTAMP')) {
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
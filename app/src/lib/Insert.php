<?php

require_once("services/Database.php");


class Insert
{

    //Function Show Columns with ID
    //TODO move this to Common.php
    public static function showColumns($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table";


        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);


        return $types;
    }

        //Function Show Columns without ID
    //TODO combine this function with the showcolumns function, create variable ID, if variable is null put without ID
    //TODO move this to Common.php
    public static function showColumnsWithoutID($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table WHERE field NOT LIKE 'id'";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);


        return $types;
    }


    //TODO move to common.php
    public static function showRequiredColumns($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table WHERE `Null` = 'No' AND field NOT LIKE 'id'";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);


        return $types;
    }

    //TODO move to common.php
    //Combine with showrequiredcolumns function
    public static function showRequiredColumnsWhithID($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table WHERE `Null` = 'No'";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);


        return $types;
    }



    //Function create insert query structure
    public static function makeInsertQuery($table, $fields, $types)
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






    public static function missingFieldsInsert($fieldsKeys,$requiredColumns,$allColumns){

        $allColumnsMap = array_map('map', $allColumns);
        $requiredColumnsMap = array_map('map', $requiredColumns);

        $columnsCorrect = false;
        $columnsRequired = false;
        $columnsRequiredMissing = 0;


        for ($x = 0; $x < count($fieldsKeys); $x++) {
            for ($y = 0; $y < count($allColumns); $y++) {
                if ($fieldsKeys[$x] == $allColumnsMap[$y]) {
                    $columnsCorrect = true;
                }
            }
            if($columnsCorrect == false){
                return array('result' => false, 'message' => ''.$fieldsKeys[$x].' not exist in table');

            }
            $columnsCorrect = false;
        }




        for ($x = 0; $x < count($fieldsKeys); $x++) {
            for ($y = 0; $y < count($requiredColumnsMap); $y++) {
                if ($fieldsKeys[$x] == $requiredColumnsMap[$y]) {
                    $columnsRequired = true;
                }
            }
            if($columnsRequired == false){
                unset($fieldsKeys[$x++]);
                Sort($fieldsKeys); 

            }

            $columnsRequired = false;
        }





        for ($x = 0; $x < count($requiredColumnsMap); $x++) {
            for ($y = 0; $y < count($fieldsKeys); $y++) {

                if ($requiredColumnsMap[$x] ==$fieldsKeys[$y]) {
                    
                    $columnsRequiredMissing++;
                }
            }
            if($columnsRequiredMissing == 0){
                return array('result' => false, 'message' => 'Missing field '.$requiredColumnsMap[$x].'');

            }

            $columnsRequiredMissing = 0;
        }
       


        return true;

    }
}



//TODO delete this function
function map($data)
{
    return $data[0];
}

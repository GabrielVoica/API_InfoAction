<?php

require_once("services/Database.php");

class Common
{


    //Function Exist ID
    public static function existID($values)
    {
        $database = new Database();
        $database->connect();

        if (count($values) == 1) {
            return true;
        }

        //Select
        $querydelete = "SELECT * FROM $values[0] WHERE id = $values[1]";
        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_all($data);


        if ($data == null) {
            return array('result' => false, 'message' => 'ID not exist');
        }

        return true;
    }


    //Function Show Columns with ID
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


    public static function missingFieldsInsert($fieldsKeys, $requiredColumns, $allColumns)
    {

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
            if ($columnsCorrect == false) {
                return array('result' => false, 'message' => '' . $fieldsKeys[$x] . ' not exist in table');
            }
            $columnsCorrect = false;
        }


        for ($x = 0; $x < count($fieldsKeys); $x++) {
            for ($y = 0; $y < count($requiredColumnsMap); $y++) {
                if ($fieldsKeys[$x] == $requiredColumnsMap[$y]) {
                    $columnsRequired = true;
                }
            }
            if ($columnsRequired == false) {
                unset($fieldsKeys[$x++]);
                Sort($fieldsKeys);
            }

            $columnsRequired = false;
        }


        for ($x = 0; $x < count($requiredColumnsMap); $x++) {
            for ($y = 0; $y < count($fieldsKeys); $y++) {

                if ($requiredColumnsMap[$x] == $fieldsKeys[$y]) {

                    $columnsRequiredMissing++;
                }
            }
            if ($columnsRequiredMissing == 0) {
                return array('result' => false, 'message' => 'Missing field ' . $requiredColumnsMap[$x] . '');
            }

            $columnsRequiredMissing = 0;
        }



        return true;
    }

    //TODO move to common.php
    public static function randomCode()
    {
        $lenght = 8;
        $parameters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUWXYZ";
        $finalResult = "";

        for ($i = 0; $i < $lenght; $i++) {
            $index = rand(0, strlen($parameters) - 1);
            $finalResult .= $parameters[$index];
        }

        return $finalResult;
    }


    public static function getLink()
    {


        $lenght = 30;
        $parameters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $finalResult = "";
        
        for($i = 0; $i < $lenght; $i++){
            $index = rand(0,strlen($parameters) - 1);
            $finalResult .= $parameters[$index];
        }
        
        $folderPath = __DIR__;
        $postdata = file_get_contents("php://input");
        if (!empty($postdata)) {
            $request = json_decode($postdata);
            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath ."..\..\user_pictures/". $finalResult . '.png';
            if (file_put_contents($file, $image_base64)) {
                $response[] = array('sts' => true, 'msg' => 'Successfully uploaded');
            }else{
                $response[] = array('sts' => false, 'msg' => 'error');
            }
        }

        $path = "http://localhost/API_InfoAction/app/src/user_pictures/".$finalResult .'.png';
        return $path;
    }

    public static function makeMarkKeys($fields, $columns){

        $keys = array_keys($fields);
        $values = array_values($fields);
        $result = $fields;


        for ($x = 0; $x < count($columns); $x++) {
            for ($y = 0; $y < count($fields); $y++) {
                if ($keys[$y] == $columns[$x][0] && !str_contains($columns[$x][1], 'int') && !str_contains($values[$y], 'CURRENT_TIMESTAMP')) {

                    $result[$keys[$y]] = "'$values[$y]'";
                }
            }
        }


        return $result;
    }
}


function map($data)
{
    return $data[0];
}
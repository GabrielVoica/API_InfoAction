<?php

require_once("services/Database.php");

class Validator
{


    //TODO move to common.php
    public static function isExist($table, $tablefield, $value)
    {
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table WHERE $tablefield = '$value'";


        $result = $database->getConnection()->query($query);

        if (mysqli_num_rows($result) == 0) {
            return true;
        } else {
            return false;
        }
    }

    //TODO move to common.php and combine with isExist
    public static function isExistNumber($table, $tablefield, $value)
    {
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table WHERE $tablefield = $value";
        $result = $database->getConnection()->query($query);


        if (mysqli_num_rows($result) == 0) {
            return true;
        } else {
            return false;
        }
    }

    //Function exist table
    //TODO move this function in Common.php
    public static function isExistTable($table)
    {
        $database = new Database();
        $database->connect();

        $querydelete = "show tables like '$table'";
        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_all($data);

        if ($data == null) {
            return array('result' => false, 'message' => 'ID not exist');
        }
        return true;
    }

    public static function isNull($value)
    {
        if ($value[1] == null) {
            return array('result' => false, 'message' => '' . $value[1] . ' : is null ');
        } else {
            return true;
        }
    }


    //Function isNumber
    public static function isNumber($value)
    {
        if (preg_match('/[^a-z]+/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    //Function is Email
    public static function isEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    //Function is Text
    public static function isText($value)
    {
        if (preg_match('/[^a-z]+/', $value)) {
            return false;
        } else {
            return true;
        }
    }

    //Min 5 characters, max 20 characters, only numbers and letters valid
    public static function isName($value)
    {
        if (preg_match("/[A-Za-z0-9]+/", $value)) {
            return true;
        } else {
            return false;
        }
    }


    //Function lenght
    public static function isLenght($value, $table, $column, $min_lenght, $max_lenght)
    {

        if ($max_lenght == null) {
            $database = new Database();
            $database->connect();
            $columns_show = "SHOW COLUMNS FROM $table WHERE Field = '$column'";


            $types = $database->getConnection()->query($columns_show);
            $types = mysqli_fetch_all($types);


            $max_lenght = $types[0][1];
            $max_lenght = substr($max_lenght, 0, -1);
            $max_lenght = substr($max_lenght, 8);
        }



        if (strlen($value) >= $min_lenght & strlen($value) <= $max_lenght) {
            return true;
        } else {
            return array('result' => false, 'message' => '' . $column . ' :Length must be ' . $min_lenght . '-' . $max_lenght . '');
        }
    }


    //One uppercase letter, numbers and letters, min-width 8 chars
    public static function isPassword($value)
    {
        $error = "";


        if (!preg_match("#[0-9]+#", $value)) {
            $error .= "Password must include at least one number!";
        }
        if (!preg_match("#[a-z]+#", $value)) {
            $error .= "Password must include at least one letter!";
        }
        if (!preg_match("#[A-Z]+#", $value)) {
            $error .= "Password must include at least one CAPS!";
        }
        if ($error != "") {
            return array('result' => false, 'message' => $error);
        } else {
            return true;
        }
    }
}

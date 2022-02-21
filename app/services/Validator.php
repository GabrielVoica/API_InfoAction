<?php

require_once("Database.php");

class Validator
{


    public static function isExist($table,$tablefield,$value){
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table WHERE $tablefield = '$value'";
        $result = $database->getConnection()->query($query);
        


        if(mysqli_num_rows($result) == 0){
            return true;
        }
        else{
            return false;
        }

    }


    public static function isNumber($value)
    {


        if (preg_match('/[^0-9]+/', $value)) {
            return true;
        } else {
            return false;
        }
    }


    public static function isEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }


    public static function isText($value)
    {
        if (preg_match('/[a-z]+/', $value)) {
            return array('result' => false, 'message' => 'Is not Text');
        } else {
            return false;
        }
    }

    //Min 5 characters, max 20 characters, only numbers and letters valid
    public static function isName($value,$min_character,$max_character)
    {
        if (preg_match('/^[A-Za-z][A-Za-z0-9]{'.$min_character.','.$max_character.'}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    //One uppercase letter, numbers and letters, min-width 8 chars
    public static function isPassword($value)
    {
        $error = "";

        if (strlen($value) < 8) {
            $error .= "Password too short!";
        }
        if (strlen($value) > 20) {
            $error .= "Password too long!";
        }
        if (strlen($value) < 8) {
            $error .= "Password too short!";
        }
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
            return false;
        } else {
            return true;
        }
    }



}

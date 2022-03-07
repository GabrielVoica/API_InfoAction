<?php

require_once("Database.php");


class Get
{

    public static function showColumns($table)
    {
        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM $table";

        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);

        return array('result' => false, 'message' => $types);
    }

}   
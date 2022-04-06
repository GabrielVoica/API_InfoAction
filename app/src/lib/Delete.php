<?php

require_once("services/Database.php");

class Delete
{

    //Function Delete Table
    public static function deleteTable($table)
    {
        $database = new Database();
        $database->connect();

        $querydelete = "DROP TABLE $table";

        return $querydelete;
    }

  


    //Delete row from specefic table, but with specific field in where
    public static function deleteRow($table, $value, $field)
    {

        $database = new Database();
        $database->connect();

        if ($value == null) {
            $querydelete = "DELETE FROM $table";
        } else {
            $querydelete = "DELETE FROM $table WHERE $field = $value";
        }

        return $querydelete;
    }


    //Function delete EVENT
    //TODO create variable TYPE (Event, Table) and put Togheter DeleteTable, and change name by DeleteType
    public static function deleteEventUpdatePoints($EventName)
    {

        $querydelete = "DROP EVENT updatePoints_$EventName";

        return $querydelete;
    }


    public static function deleteFile($route, $file){

        $delete = unlink( $route.''.$file);
        
        if(!$delete){
            return array('result' => false, 'message' => 'File not exist');
        }

        return array('result' => true, 'message' => null);

    }
}

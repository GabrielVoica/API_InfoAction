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
    public static function deleteRow($table, $value)
    {
        $keys = array_keys($value);
        $values = array_values($value);

        $database = new Database();
        $database->connect();

        if ($value == null) {
            $query = "DELETE FROM $table";
        } else {
            $query = "DELETE FROM $table WHERE "; 
            
            for ($x = 0; $x < count($keys); $x++) {
                $query .= "$keys[$x] = $values[$x] AND ";
            }
            $query = substr($query, 0, -4);
        }

        return $query;
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

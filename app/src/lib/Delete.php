<?php

require_once("services/Database.php");

class Delete
{

  //Function Delete Table
  public static function deleteTable($table){
    $database = new Database();
    $database->connect();

    $querydelete = "DROP TABLE $table";
    
    return $querydelete;
}

    //Delete row from specific table
        //TODO Delete this function and replace with deleteRowWithField
    public static function deleteRow($table, $id){
       
        $database = new Database();
        $database->connect();

        //IF if is null, delete all rows
        if($id == null){
            $querydelete = "DELETE FROM $table";
        }
        else{
            $querydelete = "DELETE FROM $table WHERE id = $id ";

        }
        
        return $querydelete;
    }


    //Delete row from specefic table, but with specific field in where
    public static function deleteRowWithField($table, $id,$field){
       
        $database = new Database();
        $database->connect();

        if($id == null){
            $querydelete = "DELETE FROM $table";
        }
        else{
            $querydelete = "DELETE FROM $table WHERE $field = $id ";

        }
        
        return $querydelete;
    }


    //Function delete EVENT
    //TODO create variable TYPE (Event, Table) and put Togheter DeleteTable, and change name by DeleteType
    public static function deleteEventUpdatePoints($EventName){
   
        $querydelete = "DROP EVENT updatePoints_$EventName";

        return $querydelete;
    }

    }


 





 


   


    


 




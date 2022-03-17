<?php

require_once("services/Database.php");

class Delete
{


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


    //Function Delete Table
    public static function deleteTable($table){
        $database = new Database();
        $database->connect();

        $querydelete = "DROP TABLE $table";
        
        return $querydelete;
    }


    //Function Exist ID
    //TODO move this function in Common.php
    public static function existID($values){
        $database = new Database();
        $database->connect();

        if(count($values) == 1){
            return true;
        }

        //Select
        $querydelete = "SELECT * FROM $values[0] WHERE id = $values[1]";
        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_all($data);


        if($data == null){
            return array('result' => false, 'message' => 'ID not exist');

        }
        
        return true;
        
    }


    //Function exist table
    //TODO move this function in Common.php
    public static function ExistTable($table){
        $database = new Database();
        $database->connect();

        $querydelete = "show tables like '$table'";
        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_all($data);

        if($data == null){
            return array('result' => false, 'message' => 'ID not exist');

        }
        return true;
    }

    //Function delete EVENT
    //TODO create variable TYPE (Event, Table) and put Togheter DeleteTable, and change name by DeleteType
    public static function deleteEventUpdatePoints($EventName){
   
        $querydelete = "DROP EVENT updatePoints_$EventName";

        return $querydelete;
    }

    }


 





 


   


    


 




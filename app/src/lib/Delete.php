<?php

require_once("services/Database.php");

class Delete
{


    public static function deleteRow($table, $id){
       
        $database = new Database();
        $database->connect();

        if($id == null){
            $querydelete = "DELETE FROM $table";
        }
        else{
            $querydelete = "DELETE FROM $table WHERE id = $id ";

        }
        

      
        return $querydelete;
    }


    public static function deleteTable($table){
        $database = new Database();
        $database->connect();

        $querydelete = "DROP TABLE $table";
        
        return $querydelete;
    }


    public static function existID($values){
        $database = new Database();
        $database->connect();



        if(count($values) == 1){
            return true;
        }

        $querydelete = "SELECT * FROM $values[0] WHERE id = $values[1]";
        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_all($data);


        if($data == null){
            return array('result' => false, 'message' => 'ID not exist');

        }
        
        return true;
        
    }
    }


 





 


   


    


 




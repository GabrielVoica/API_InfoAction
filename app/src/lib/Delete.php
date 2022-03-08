<?php

require_once("services/Database.php");

class Delete
{


    public static function deleteRow($values){
       
        $database = new Database();
        $database->connect();

        if(count($values) == 1){
            $querydelete = "DELETE FROM $values[0]";
        }
        else{
            $querydelete = "DELETE FROM $values[0] WHERE id = $values[1] ";

        }
        


      
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


 





 


   


    


 




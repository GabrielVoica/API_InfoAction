<?php

require_once("services/Database.php");
require_once("services/Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("services/Insert.php");


class Session implements Model
{

    public static function get($id = null, array $fields = null)
    {

    }



    public static function getField($id)
    {
   


        if(Validator::isExistNumber('cookies','user_id',$id[1])){
            return array('result' => false, 'message' => ''.$id[1].' not exist, try another');
        }
     
        

        $query = "SELECT id FROM cookies where";
        $query = "$query user_id = $id[1]";


 
        
    

        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        return array('result' => false, 'message' => $data);
    }
  

    public static function getAll()
    {
     
    }


    public static function insert(array $fields = null)
    {

    }
       
        

    public static function delete($id)
    {
    }

    public static function deleteAll()
    {
    }

    public static function update(array $fields = null)
    {
    }
}

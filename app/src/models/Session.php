<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


require_once("src/lib/Insert.php");
require_once("src/lib/Validator.php");
require_once("src/lib/Common.php");
require_once("src/lib/Get.php");




class Session implements Model
{

    public static function get($id = null, array $fields = null)
    {

    }



    public static function getField($id)
    {
        $database = new Database();
        $database->connect();
       
        $columns = Common::showColumns('user');
        $idSession['email'] = $id[1];
        $fieldsMark = Common::makeMarkKeys($idSession ,$columns);   


        if(Validator::isExist('user','email',$fieldsMark['email'])){
            return array('result' => false, 'message' => ''.$fieldsMark['email'].' not exist, try another');
        }

        $fieldsInput = ['email' => $fieldsMark['email']];
        $query = Get::getDataField('user', $fieldsInput);

        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
    
        $fieldsInput = ['user_id' => $data['id']];
        $query = Get::getDataField('cookies', $fieldsInput);
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

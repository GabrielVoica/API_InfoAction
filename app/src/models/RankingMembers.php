<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


require_once("src/lib/Validator.php");
require_once("src/lib/Insert.php");


class RankingMembers implements Model
{

    public static function get($id = null, array $fields = null)
    {

    }

    public static function getAll()
    {
      
    }


    public static function insert(array $fields = null)
    {
        
        $columnsRequired = Insert::showRequiredColumns('user');
        $columnsAll = Insert::showColumnsWithoutID('user');

        $fieldsKeys = array_keys($fields);

        

        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }


        if(isset($fields['nick_name'])){
             //Works
            if (!Validator::isText($fields['nick_name'])) {
                return array('result' => false, 'message' => 'Nickname must include only letters');
            }
            
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['nick_name'],'user','nick_name',5,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
    
            //Works
            if(!Validator::isExist('user','nick_name',$fields['nick_name'])){
                return array('result' => false, 'message' => ''.$fields['nick_name'].' exist, use another');
            }

        }


        if(isset($fields['email'])){
            //Works
            if(!Validator::isEmail($fields['email'])) {
                return array('result' => false, 'message' => 'Email is not correct');
            } 
    
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['email'],'user','email',10,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
    
            //Works
            if(!Validator::isExist('user','email',$fields['email'])){
                return array('result' => false, 'message' => ''.$fields['email'].' exist, use another');
            }
        }

    
        if(isset($fields['password'])){

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['password'],'user','password',8,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            $PasswordCheck = Validator::isPassword($fields['password']);
            if ($PasswordCheck > 1) {
                return array('result' => false, 'message' => $PasswordCheck['message']);
            } else {
                $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
            }
        }



        if(isset($fields['name'])){
            //Works
            if(!Validator::isText($fields['name'])){
                return array('result' => false, 'message' => 'Name must include only letters');

            }

           //Works
            $nameLenghtReturn = Validator::isLenght($fields['name'],'user','name',5,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }

      

        if(isset($fields['lastname'])){
            //Works
            if(!Validator::isText($fields['lastname'])){
                return array('result' => false, 'message' => 'Lastname must include only letters');

            }
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['lastname'],'user','lastname',5,null);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if(isset($fields['center_id'])){
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['center_id'],'user','center_id',1,5);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if($fields['center_id'] < 0){
                return array('result' => false, 'message' => 'No negative numbers');

            }
            //Works
            if(!Validator::isNumber($fields['center_id'])){
                return array('result' => false, 'message' => 'Center ID : Only numbers');

            }
        
        }

        if(isset($fields['rol'])){
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['rol'],'user','rol',1,1);
            if($nameLenghtReturn > 1){
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if(!Validator::isNumber($fields['rol'])){
                return array('result' => false, 'message' => 'Rol : Only numbers');
            }

            //Works
            if($fields['rol'] != 0 & $fields['rol'] != 1){
                return array('result' => false, 'message' => 'Rol : Only 0 => Student or 1 => Teacher');

            }
          
        }


        $database = new Database();
        $database->connect();

        $types = Insert::showColumns('user');
        $query = Insert::makeInsertQuery('user', $fields, $types);


        $data = $database->getConnection()->query($query);

        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
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

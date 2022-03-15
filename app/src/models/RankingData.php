<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Create.php");
require_once("src/lib/Validator.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Get.php");
require_once("src/lib/Update.php");







class RankingData implements Model
{

    public static function get($id = null, array $fields = null)
    {
        $database = new Database();
        $database->connect();

       
        $query = Get::getTable('rankingdata',$id,'id');
        
        $data = $database->getConnection()->query($query);

       
        if ($data === false) {
            return array('result' => false, 'Error query select');
        }
        if (mysqli_num_rows($data) == 0) {
            return array('result' => false, 'message' => NotFoundError::throw()['message']);
        }

        $data = mysqli_fetch_assoc($data);

        return array('result' => false, 'message' => $data);
    }

    public static function getAll()
    {
        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query('SELECT * FROM user');
        $data = mysqli_fetch_assoc($data);

        return $data;
    }


    public static function insert(array $fields = null)
    {

        $columnsRequired = Insert::showRequiredColumns('rankingdata');
        $columnsAll = Insert::showColumnsWithoutID('rankingdata');

        $fieldsKeys = array_keys($fields);

        if(isset($fields['creationdate'])){
            if($fields['creationdate'] == null){
                $fields['creationdate'] = "CURRENT_TIMESTAMP";
            }
        }    

        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }

    
        if(isset($fields['ranking_name'])){
            //Works
           if (!Validator::isText($fields['ranking_name'])) {
               return array('result' => false, 'message' => 'Ranking Name must include only letters');
           }
           
           //Works
           $LenghtReturn = Validator::isLenght($fields['ranking_name'],'rankingdata','ranking_name',4,null);
           if($LenghtReturn > 1){
               return array('result' => false, 'message' => $LenghtReturn['message']);
           }
   
           //Works
           if(!Validator::isExist('rankingdata','ranking_name',$fields['ranking_name'])){
               return array('result' => false, 'message' => ''.$fields['ranking_name'].' exist, use another');
           }

       }

        if(isset($fields['description'])){
            $fields['description'] = str_replace("-"," ",$fields['description']);
               
        }



       if(isset($fields['teacher_id'])){
        if(!Validator::isNumber($fields['teacher_id'])){
            return array('result' => false, 'message' => 'Teacher_ID : Only numbers');

        }
       }



        $database = new Database();
        $database->connect();

        $types = Insert::showColumns('rankingdata');
        $query = Insert::makeInsertQuery('rankingdata', $fields, $types);

        $rankingstructure = array(
            "id" => "int NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "nick_name" => "varchar(20)",
            "name_lastname" => "varchar(40)",
            "points" => "int",
            "status" => "int",
            "level" => 'int'
        );


        $querycreate = Create::makeCreateQuery($fields['ranking_name'],$rankingstructure);


        $data = $database->getConnection()->query($query);
        if ($data) {
            $querycreate = Create::makeCreateQuery($fields['ranking_name'],$rankingstructure);
            $data = $database->getConnection()->query($querycreate);

            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }


    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();

        $queryidexist = Delete::existID($fields);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        

     
        $querydelete = Get::getDataField($fields[0],$fields[1],'id');

        $data = $database->getConnection()->query($querydelete);
        $data = mysqli_fetch_assoc($data);


        $tableDelete = "R_".$data['ranking_name'];



        if(count($fields) == 1){
            $querydelete = Delete::deleteRow($fields[0],null);
        }
        else{
            $querydelete = Delete::deleteRow($fields[0],$fields[1]);

        $data = $database->getConnection()->query($querydelete);



        if ($data) {
            $querydelete = Delete::deleteTable($tableDelete);
            $data = $database->getConnection()->query($querydelete);

            return array('result' => false, 'message' => 'The insert has been made');

        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }

}
   

    public static function update(array $fields = null)
    {
        $database = new Database();
        $database->connect();



        $columnsRequired = Insert::showRequiredColumnsWhithID('rankingdata');
        $columnsAll = Insert::showColumns('rankingdata');

        $fieldsKeys = array_keys($fields);

     
        $CheckFieldsInsert = Insert::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
        if($CheckFieldsInsert > 1){
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);

        }

    
        if(isset($fields['ranking_name'])){
            //Works
           if (!Validator::isText($fields['ranking_name'])) {
               return array('result' => false, 'message' => 'Ranking Name must include only letters');
           }
           
           //Works
           $LenghtReturn = Validator::isLenght($fields['ranking_name'],'rankingdata','ranking_name',4,null);
           if($LenghtReturn > 1){
               return array('result' => false, 'message' => $LenghtReturn['message']);
           }
   
           //Works
           if(!Validator::isExist('rankingdata','ranking_name',$fields['ranking_name'])){
               return array('result' => false, 'message' => ''.$fields['ranking_name'].' exist, use another');
           }

       }

        if(isset($fields['description'])){
            $fields['description'] = str_replace("-"," ",$fields['description']);
               
        }



       if(isset($fields['teacher_id'])){
        if(!Validator::isNumber($fields['teacher_id'])){
            return array('result' => false, 'message' => 'Teacher_ID : Only numbers');

        }
       }

       if(isset($fields['teacher_id'])){
        if(!Validator::isNumber($fields['teacher_id'])){
            return array('result' => false, 'message' => 'Teacher_ID : Only numbers');

        }
       }

       if(isset($fields['members'])){
        if(!Validator::isNumber($fields['members'])){
            return array('result' => false, 'message' => 'Members : Only numbers');

        }
       }


        if(!isset($fields['id'])){
            return array('result' => false, 'message' =>  'ID field not exist');

        }

        $queryidexist = Update::existID('rankingdata',$fields['id']);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        $types = Insert::showColumns('rankingdata');
        $querydelete = Update::updateRow('rankingdata',$fields,$types);
        $data = $database->getConnection()->query($querydelete);



        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

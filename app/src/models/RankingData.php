<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


require_once("src/lib/Common.php");
require_once("src/lib/Create.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Get.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Update.php");
require_once("src/lib/Validator.php");
require_once("src/models/Ranking.php");





class RankingData implements Model
{

    public static function get($id = null, array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $idData['code'] = $id;
        $idMark = Common::makeMarkKeys($idData,$columns);


        $query = Get::getDataField('rankingdata',$idMark['code'],'code');
        $data = $database->getConnection()->query($query);



        if ($data === false) {
            return array('result' => false, 'Error query select');
        }
        if (mysqli_num_rows($data) == 0) {
            return array('result' => false, 'message' => null);
        }

        $data = mysqli_fetch_assoc($data);

        return array('result' => true, 'message' => null, 'data' => $data);
    }

    public static function getAll()
    {
        $database = new Database();
        $database->connect();
        $query = Get::getAllData('rankingdata');

        $data = $database->getConnection()->query($query);
     

        if ($data === false) {
            return array('result' => false, 'Error query select');
        }
        if (mysqli_num_rows($data) == 0) {
            return array('result' => false, 'message' => "0 rows");
        }

        while($array = mysqli_fetch_assoc($data)){
            $wishlist[] = $array;
        }
 

        return array('result' => true, 'message' => null, 'data' => $wishlist);
    }


    public static function insert(array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields,$columns);


        $columnsRequired = Common::showRequiredColumns('rankingdata');
        $columnsAll = Common::showColumnsWithoutID('rankingdata');
        $fieldsKeys = array_keys($fields);


     
        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
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
           if(!Validator::isExist('rankingdata','ranking_name',$fieldsMark['ranking_name'])){
               return array('result' => false, 'message' => ''.$fields['ranking_name'].' exist, use another');
           }

       }

        if(isset($fields['description'])){
            $fields['description'] = str_replace("-"," ",$fields['description']);
               
        }

        if(isset($fields['creationdate'])){
            if($fields['creationdate'] != 'CURRENT_TIMESTAMP'){
                return array('result' => false, 'message' => 'Creation Date: Put CURRENT_TIMESTAMP in variable');

            }
               
        }


        if(isset($fields['code'])){
            if($fields['code'] == 'random'){
                $fields['code'] = Common::randomCode();
            }
            else{
                return array('result' => false, 'message' => 'Code: Put random in value');

            }               
        }

       if(isset($fields['teacher_id'])){
        if(!Validator::isNumber($fields['teacher_id'])){
            return array('result' => false, 'message' => 'Teacher_ID : Only numbers');

        }
       }


        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields,$columns);
        $query = Insert::makeInsertQuery('rankingdata',$fieldsMark);
        $data = $database->getConnection()->query($query);


        $tablename = "R_".$fields['ranking_name'];

        $rankingstructure = array(
            "id" => "int",
            "nick_name" => "varchar(20)",
            "name_lastname" => "varchar(40)",
            "points" => "int",
            "status" => "int",
            "level" => 'int'
        );


        if ($data) {
        $query = Create::makeCreateQuery($tablename,$rankingstructure);
        $data = $database->getConnection()->query($query);
             //Create Event for table
        $query = Create::createEventUpdadePoints($tablename);
        $data = $database->getConnection()->query($query);

        $datainput['id'] = $fields['teacher_id'];
        $datainput['code'] = $fields['code'];        
        $query = Ranking::insert($datainput);
        print_r($query);
        return array('result' => true, 'message' => null);

        }
        else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }


         
      
    }


    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('rankingdata');
        $fieldsMark['table'] = $fields[0];
        $fieldsVal['code'] = $fields[1];
        $fieldsMark = Common::makeMarkKeys($fieldsVal,$columns);
        $fieldsMark['table'] = $fields[0];


        $queryidexist = Validator::isExist($fieldsMark['table'],'code',$fieldsMark['code']);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  'Code not exist');

        }

     
        $query = Get::getDataField($fieldsMark['table'],$fieldsMark['code'],'code');
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);


        $table = "R_".$data['ranking_name'];

        $query = Delete::deleteRow('rankingdata',$fieldsMark['code'],'code');
        $data = $database->getConnection()->query($query);



        if ($data) {
            $query = Delete::deleteTable($table);
            $data = $database->getConnection()->query($query);

            $query = Delete::deleteRow('rankingmembers',$fieldsMark['code'],'code_ranking');
            $data = $database->getConnection()->query($query);

            $query = Delete::deleteEventUpdatePoints($table);
            $data = $database->getConnection()->query($query);


            return array('result' => true, 'message' => null);

        } else {
            return array('result' => false, 'message' => null);
        }
    }


   
    public static function update(array $fields = null)
    {
        $database = new Database();
        $database->connect();



        $columnsRequired = Common::showRequiredColumnsWhithID('rankingdata');
        $columnsAll = Common::showColumns('rankingdata');

        $fieldsKeys = array_keys($fields);

     
        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys,$columnsRequired,$columnsAll);
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


        if(isset($fields['code'])){
            if($fields['code'] == 'random'){
                $fields['code'] = Common::randomCode();
            }               
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

        $queryidexist = Validator::isExist('rankingdata','id',$fields['id']);
        if($queryidexist > 1){
            return array('result' => false, 'message' =>  $queryidexist['message']);

        }

        $types = Common::showColumns('rankingdata');
        $querydelete = Update::updateRow('rankingdata',$fields,$types);
        $data = $database->getConnection()->query($querydelete);



        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

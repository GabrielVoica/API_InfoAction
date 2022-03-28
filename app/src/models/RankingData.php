<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Create.php");
require_once("src/lib/Validator.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Get.php");
require_once("src/lib/Update.php");
require_once("src/lib/Common.php");


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

        $columnsRequired =  Common::showRequiredColumns('rankingdata');
        $columnsAll = Common::showColumnsWithoutID('rankingdata');

        $fieldsKeys = array_keys($fields);

        if(isset($fields['creationdate'])){
            if($fields['creationdate'] == null){
                $fields['creationdate'] = "CURRENT_TIMESTAMP";
            }
        }    

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

        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $quotesFields = Common::makeMarkKeys($fields,$columns);
        $query = Insert::makeInsertQuery('rankingdata', $fields, $quotesFields);

        $rankingstructure = array(
            "id" => "int",
            "nick_name" => "varchar(20)",
            "name_lastname" => "varchar(40)",
            "points" => "int",
            "status" => "int",
            "level" => 'int'
        );


        $tablename = "R_".$fields['ranking_name'];
        $querycreate = Create::makeCreateQuery($tablename,$rankingstructure);

        $data = $database->getConnection()->query($query);
        if ($data) {
            $querycreate = Create::makeCreateQuery($tablename,$rankingstructure);
            $data = $database->getConnection()->query($querycreate);
            
        //Create Event for table
        $query = Create::createEventUpdadePoints($tablename);
        $data = $database->getConnection()->query($query);

            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }


    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();

        $queryidexist = Validator::isExist($fields[0],'id',$fields[2]);
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

            $querydelete = Delete::deleteRowWithField('rankingmembers','id_ranking',$fields[1]);
            $data = $database->getConnection()->query($querydelete);

            $querydelete = Delete::deleteEventUpdatePoints($tableDelete);
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

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
        $idMark = Common::makeMarkKeys($idData, $columns);


        $fieldsInput = ['code' => $idMark['code']];
        $query = Get::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);




        if ($data === false) {
            return array('result' => false, 'Error query select');
        }
        if (mysqli_num_rows($data) == 0) {
            return array('result' => false, 'message' => null);
        }

        $data = mysqli_fetch_assoc($data);


        $fieldsInput = ['id' => $data['teacher_id']];
        $query = Get::getDataField('user', $fieldsInput);
        $datauser = $database->getConnection()->query($query);
        $datauser = mysqli_fetch_assoc($datauser);

        $data['nick_name'] = $datauser['nick_name'];

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

        while ($array = mysqli_fetch_assoc($data)) {
            $wishlist[] = $array;
        }


        return array('result' => true, 'message' => null, 'data' => $wishlist);
    }


    public static function insert(array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        $columnsRequired = Common::showRequiredColumns('rankingdata');
        $columnsAll = Common::showColumnsWithoutID('rankingdata');
        $fieldsKeys = array_keys($fields);



        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);
        }



        if (isset($fields['ranking_name'])) {
            //Works
            $fields['ranking_name'] = str_replace("%20", " ", $fields['ranking_name']);


            if (!Validator::isText($fields['ranking_name'])) {
                return array('result' => false, 'message' => 'Ranking Name must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['ranking_name'], 'rankingdata', 'ranking_name', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('rankingdata', 'ranking_name', $fieldsMark['ranking_name'])) {
                return array('result' => false, 'message' => '' . $fields['ranking_name'] . ' exist, use another');
            }
        }

        if (isset($fields['description'])) {
            $fields['description'] = str_replace("%20", " ", $fields['description']);

            if (!Validator::isText($fields['description'])) {
                return array('result' => false, 'message' => 'Description must include only letters');
            }


            $LenghtReturn = Validator::isLenght($fields['ranking_name'], 'rankingdata', 'description', 5, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
        }

        if (isset($fields['creationdate'])) {
            if ($fields['creationdate'] != 'CURRENT_TIMESTAMP') {
                return array('result' => false, 'message' => 'Creation Date: Put CURRENT_TIMESTAMP in variable');
            }
        }


        if (isset($fields['code'])) {
            if ($fields['code'] == 'random') {
                $fields['code'] = Common::randomCode();
            } else {
                return array('result' => false, 'message' => 'Code: Put random in value');
            }
        }

        if (isset($fields['teacher_id'])) {
            if (!Validator::isNumber($fields['teacher_id'])) {
                return array('result' => false, 'message' => 'Teacher_ID : Only numbers');
            }
        }

        $fields['members'] = 0;
        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $query = Insert::makeInsertQuery('rankingdata', $fieldsMark);
        $data = $database->getConnection()->query($query);


        $tablename = "R_" . $fields['ranking_name'];
        $tablenameTask = "R_" . $fields['ranking_name']."_Task";
        $tablenameNotes = "R_" . $fields['ranking_name']."_Notes";



        $rankingstructure = array(
            "id" => "int",
            "nick_name" => "varchar(20)",
            "name_lastname" => "varchar(40)",
            "points" => "int",
            "pointsSpend" => "int",
            "level" => 'int',
            "status" => "int",
            "responsabilidad" => 'int',
            "cooperacion" => 'int',
            "autonomia_e_iniciativa" => 'int',
            "gestion_emocional" => 'int',
            "habilidades_de_pensamiento" => 'int'

        );

        $rankingstructureTask = array(
            "id" => "int NOT NULL AUTO_INCREMENT",
            "task_name" => "varchar(20)",
            "description" => "varchar(40)",
            "creationdate" => "datetime",
            "PRIMARY KEY" => "(id)"

        );


        $rankingstructureNotes = array(
            "id" => "int",
            "id_valued" => "int",
            "id_evaluator" => "int",
            "amount" => "int",
            "creationdate" => "datetime",

        );



        if ($data) {
            $query = Create::makeCreateQuery($tablename, $rankingstructure);
            $data = $database->getConnection()->query($query);

            $query = Create::makeCreateQuery($tablenameTask, $rankingstructureTask);
            print_r($query);
            $data = $database->getConnection()->query($query);

            $query = Create::makeCreateQuery($tablenameNotes, $rankingstructureNotes);
            $data = $database->getConnection()->query($query);
            //Create Event for table
            $query = Create::createEventUpdadePoints($tablename);
            $data = $database->getConnection()->query($query);

            $datainput['id'] = $fields['teacher_id'];
            $datainput['code'] = $fields['code'];

            $columns = Common::showColumns('rankingmembers');
            $fieldsMark = Common::makeMarkKeys($datainput, $columns);
            $query = Insert::makeInsertQuery('rankingmembers', $fieldsMark);
            $data = $database->getConnection()->query($query);
            return array('result' => true, 'message' => null);
        } else {
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
        $fieldsMark = Common::makeMarkKeys($fieldsVal, $columns);
        $fieldsMark['table'] = $fields[0];


        $queryidexist = Validator::isExist($fieldsMark['table'], 'code', $fieldsMark['code']);
        if ($queryidexist > 1) {
            return array('result' => false, 'message' =>  'Code not exist');
        }

        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = Get::getDataField($fieldsMark['table'], $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);


        $table = "R_" . $data['ranking_name'];
        $tableTask = "R_" . $data['ranking_name']."_Task";
        $tableNotes = "R_" . $data['ranking_name']."_Notes";


        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = Delete::deleteRow('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);

        if ($data) {

            $query = Delete::deleteTable($table);
            $data = $database->getConnection()->query($query);

            $query = Delete::deleteTable($tableTask);
            $data = $database->getConnection()->query($query);

            $query = Delete::deleteTable($tableNotes);
            $data = $database->getConnection()->query($query);


            $fieldsInput = ['code' => $fieldsMark['code']];
            $query = Delete::deleteRow('rankingmembers', $fieldsInput);
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

        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        if (isset($fields['code'])) {
            if (Validator::isExist('rankingdata', 'code', $fieldsMark['code'])) {
                return array('result' => false, 'message' => '' . $fields['code'] . ' not exist, use another');
            }
        }


        if (isset($fields['coderandom'])) {
            $wherevalue = $fieldsMark['code'];
            if ($fields['coderandom'] == 'random') {
                $fields['code'] = Common::randomCode();
                $codernadom['ranking_name'] = $fields['code'];
            }
            unset($fields['coderandom']);
        } else {
            $wherevalue = $fieldsMark['code'];
            unset($fields['code']);
        }

        $columnsRequired = Common::showRequiredColumnsWhithID('rankingdata');
        $columnsAll = Common::showColumns('rankingdata');
        $fieldsKeys = array_keys($fields);

        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);
        }



        if (isset($fields['ranking_name'])) {
            //Works
            $fields['ranking_name'] = str_replace("%20", " ", $fields['ranking_name']);

            if (!Validator::isText($fields['ranking_name'])) {
                return array('result' => false, 'message' => 'Ranking Name must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['ranking_name'], 'rankingdata', 'ranking_name', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('rankingdata', 'ranking_name', $fieldsMark['ranking_name'])) {
                return array('result' => false, 'message' => '' . $fields['ranking_name'] . ' exist, use another');
            }
        }

        if (isset($fields['description'])) {
            $fields['description'] = str_replace("%20", " ", $fields['description']);

            $LenghtReturn = Validator::isLenght($fields['description'], 'rankingdata', 'description', 5, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
        }


        if (isset($fields['teacher_id'])) {
            if (!Validator::isNumber($fields['teacher_id'])) {
                return array('result' => false, 'message' => 'Teacher_ID : Only numbers');
            }
        }

        if (isset($fields['teacher_id'])) {
            if (!Validator::isNumber($fields['teacher_id'])) {
                return array('result' => false, 'message' => 'Teacher_ID : Only numbers');
            }
        }

        if (isset($fields['members'])) {
            if (!Validator::isNumber($fields['members'])) {
                return array('result' => false, 'message' => 'Members : Only numbers');
            }
        }


        // if(!isset($fields['id'])){
        //     return array('result' => false, 'message' =>  'ID field not exist');

        // }

        // $queryidexist = Validator::isExist('rankingdata','id',$fields['id']);
        // if($queryidexist > 1){
        //     return array('result' => false, 'message' =>  $queryidexist['message']);

        // }


        $fieldsInput = ['code' => $wherevalue];
        $query = Get::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
        $oldTableName = $data['ranking_name'];

        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $query = Update::updateRow('rankingdata', $fieldsMark, 'code', $wherevalue);
        $data = $database->getConnection()->query($query);




        if ($data) {
            if (isset($codernadom['ranking_name'])) {
                $columns = Common::showColumns('rankingmembers');
                $fieldsMembers['ranking_name'] = $wherevalue;
                $coderandomMark = Common::makeMarkKeys($codernadom, $columns);
                $query = Update::updateRow('rankingmembers', $coderandomMark, 'ranking_name', $fieldsMembers['code']);
                $data = $database->getConnection()->query($query);
            }



            if (isset($fields['ranking_name'])) {

                $query = 'RENAME TABLE `R_' . $oldTableName . '` TO `R_' . $fields['ranking_name'] . '`';
                $data = $database->getConnection()->query($query);


                $query = Delete::deleteEventUpdatePoints('R_' . $oldTableName);
                $data = $database->getConnection()->query($query);

                
                $query = Create::createEventUpdadePoints('R_' . $fields['ranking_name']);
                $data = $database->getConnection()->query($query);
            }

            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

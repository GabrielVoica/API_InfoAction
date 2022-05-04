<?php

require_once("services/Database.php");
require_once("src/lib//Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Get.php");
require_once("src/lib/Common.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Update.php");




class Ranking implements Model
{

    public static function get($id = null, array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $idMark = Common::makeMarkKeys($id['code'], $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $rankingName = 'R_' . $dataRanking['ranking_name'];

        $columns = Common::showColumns($rankingName);
        $idMark = Common::makeMarkKeys($id, $columns);
        $fieldsInput = ['id' => $idMark['id-task']];
        $query = Get::getDataField($rankingName, $fieldsInput);
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




    public static function getAll($id = null)
    {

        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('rankingdata');
        $idData['code'] = $id['id-ranking'];
        $idMark = Common::makeMarkKeys($idData, $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);

        if (mysqli_num_rows($dataRanking) == 0) {
            return array('result' => false, 'message' => "0 rows");
        }

        $dataRanking = mysqli_fetch_assoc($dataRanking);
        $rankingName = 'R_' . $dataRanking['ranking_name'];


        $selectFields = array(0 => '*');
        $selectInnerFields = array(0 => 'image');
        $fieldsInput = ['status' => 0];
        $fieldsInput2 = ['status' => 1];


        $query = Get::getAllData($rankingName, 'id', $selectFields, 'INNER JOIN', 'user', 'id', $selectInnerFields,$fieldsInput);
        $query = $query . ' ORDER BY points DESC';
        $data = $database->getConnection()->query($query);

        if ($data === false) {
            return array('result' => false, 'message' => NotFoundError::throw()['message']);
        }
        if (mysqli_num_rows($data) == 0) {
            $wishlist[] = null;
        }

        while ($array = mysqli_fetch_assoc($data)) {
            $wishlist[] = $array;
        }


        $query = Get::getAllData($rankingName, 'id', $selectFields, 'INNER JOIN', 'user', 'id', $selectInnerFields,$fieldsInput2);
        $query = $query . ' ORDER BY points DESC';
        $data = $database->getConnection()->query($query);

        if (mysqli_num_rows($data) == 0) {
            $wishlist2[] = null;
        }
        while ($array = mysqli_fetch_assoc($data)) {
            $wishlist2[] = $array;
        }



        $datafinal['unaccepted'] = $wishlist;
        $datafinal['accepted'] = $wishlist2;



        return array('result' => true, 'message' => null, 'data' => $datafinal);
    }


    public static function insert(array $fields = null)
    {
        //Create Database Connection
        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $columns = Common::showColumns('user');
        $fieldsMark += Common::makeMarkKeys($fields, $columns);


        //Post: Ranking id Validators
        if (!isset($fields['code'])) {
            return array('result' => false, 'message' => 'Missing Code Ranking');
        } else {
            if (Validator::isExist('rankingdata', 'code', $fieldsMark['code'])) {
                return array('result' => false, 'message' => '' . $fields['code'] . ' not exist, use another');
            }
            $LenghtReturn = Validator::isLenght($fields['code'], 'rankingdata', 'code', 8, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
        }


        //POST: User id validators
        if (!isset($fields['id'])) {
            return array('result' => false, 'message' => 'Missing ID User');
        } else {
            if (Validator::isText($fields['id'])) {
                return array('result' => false, 'message' => 'User ID must be only numbers');
            }
            if (Validator::isExist('user', 'id', $fieldsMark['id'])) {
                return array('result' => false, 'message' => '' . $fields['id'] . ' not exist, use another');
            }
        }

        //With ranking code, get ranking name and save ranking name in variable
        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = GET::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
        $ranking_name = "R_" . $data['ranking_name'];


        //With user id, get user data and save in fields to make inserts
        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = GET::getDataField('user', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        //Fields to make insert in Ranking Rows
        $fields['id'] = $data['id'];
        $fields['nick_name'] = $data['nick_name'];
        $fields['name_lastname'] = $data['name'] . ' ' . $data['lastname'];
        $fields['points'] = 0;
        $fields['pointsSpend'] = 1000;
        $fields['status'] = 0;
        $fields['level'] = 0;
        $fields['responsabilidad'] = 0;
        $fields['cooperacion'] = 0;
        $fields['autonomia_e_iniciativa'] = 0;
        $fields['gestion_emocional'] = 0;
        $fields['habilidades_de_pensamiento'] = 0;


        //Save ranking id and user id for save in ranking members, all rows
        $rankingmembers['code'] = $fieldsMark['code'];
        $rankingmembers['id'] = $fieldsMark['id'];


        $columns = Common::showColumns($ranking_name);
        $fieldsMark = Common::makeMarkKeys($fields, $columns);



        //If ID user exist in table user, later user exist in ranking table
        if (isset($fields['id'])) {
            if (!Validator::isExist($ranking_name, 'id', $fieldsMark['id'])) {
                return array('result' => false, 'message' => '' . $fields['id'] . ' exist, use another');
            }
        }



        //Select required columns for ranking
        $columnsRequired = Common::showRequiredColumns($ranking_name);
        $columnsAll = Common::showColumns($ranking_name);


        //Delete ranking_id, user_id for make insert
        unset($fields['code']);
        unset($fieldsMark['code']);


        //Function missing NOT NULL field
        $fieldsKeys = array_keys($fields);
        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);
        }

        $query = Insert::makeInsertQuery($ranking_name, $fieldsMark);
        $data = $database->getConnection()->query($query);


        if ($data) {
            //IF query is correct, insert in raking members ranking_id and user_id    
            $ranking_name = 'rankingmembers';
            $types = Common::showColumns($ranking_name);
            $query = Insert::makeInsertQuery($ranking_name, $rankingmembers);
            $data = $database->getConnection()->query($query);


            $fieldsUpdate['members'] = 'members + 1';
            $query = Update::updateRow('rankingdata', $fieldsUpdate, 'code', $rankingmembers['code']);
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


        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $columns = Common::showColumns('user');
        $fieldsMark += Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = GET::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        $rankingName = "R_" . $data['ranking_name'];


        $tableExist = Validator::isExistTable($rankingName);
        if ($tableExist > 1) {
            return array('result' => false, 'message' =>  $tableExist['message']);
        }


        $queryidexist = Validator::isExist($rankingName, 'id', $fields['id']);
        if ($queryidexist > 1) {
            return array('result' => false, 'message' =>  $queryidexist['message']);
        }


        // if (count($fields) == 1) {
        //     $querydelete = Delete::deleteRow($fields[0], null);
        // } else {
        //     $querydelete = Delete::deleteRow($fields[0], $fields[1]);
        // }

        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Delete::deleteRow($rankingName, $fieldsInput);
        $data = $database->getConnection()->query($query);

        if ($data) {
            $fieldsInput = ['id' => $fieldsMark['id'], 'code' => $fieldsMark['code']];
            $query = Delete::deleteRow('rankingmembers', $fieldsInput);
            $data = $database->getConnection()->query($query);


            $fieldsUpdate['members'] = 'members - 1';
            $query = Update::updateRow('rankingdata', $fieldsUpdate, 'code', $fieldsMark['code']);
            $data = $database->getConnection()->query($query);


            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }

    public static function deleteAll()
    {
    }

    public static function update(array $fields = null)
    {
        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $columns = Common::showColumns('user');
        $fieldsMark += Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = Get::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data =  mysqli_fetch_assoc($data);

        $rankingName = "R_" . $data['ranking_name'];


        $wherevalue = $fields['id'];
        unset($fields['code']);
        unset($fields['id']);

        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Get::getDataField($rankingName, $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data =  mysqli_fetch_assoc($data);


        switch ($fields) {
            case isset($fields['points']):
                $fields['points'] = $fields['points'] + $data['points'];
                break;
            case isset($fields['responsabilidad']):
                $fields['responsabilidad'] = $fields['responsabilidad'] + $data['responsabilidad'];
                break;
            case isset($fields['cooperacion']):
                $fields['cooperacion'] = $fields['cooperacion'] + $data['cooperacion'];
                break;
            case isset($fields['autonomia_e_iniciativa']):
                $fields['autonomia_e_iniciativa'] = $fields['autonomia_e_iniciativa'] + $data['autonomia_e_iniciativa'];
                break;
            case isset($fields['gestion_emocional']):
                $fields['gestion_emocional'] = $fields['gestion_emocional'] + $data['gestion_emocional'];
                break;
            case isset($fields['habilidades_de_pensamiento']):
                $fields['habilidades_de_pensamiento'] = $fields['habilidades_de_pensamiento'] + $data['habilidades_de_pensamiento'];
                break;
        }

        $columns = Common::showColumns($rankingName);
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $query = Update::updateRow($rankingName, $fieldsMark, 'id', $wherevalue);
        $data = $database->getConnection()->query($query);


        if ($data) {
            return array('result' => true, 'message' => null);
        } else {
            return array('result' => false, 'message' => null);
        }
    }
}

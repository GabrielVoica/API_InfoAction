<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");
require_once("src/models/Ranking.php");


require_once("src/lib/Insert.php");
require_once("src/lib/Validator.php");
require_once("src/lib/Common.php");
require_once("src/lib/Get.php");
require_once("src/lib/Delete.php");
require_once("src/lib/update.php");






class RankingNote implements Model
{

    public static function get($id = null, array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $idData['code'] = $id['code'];
        $idMark = Common::makeMarkKeys($idData, $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Notes';

        $columns = Common::showColumns($rankingName);
        $idMark = Common::makeMarkKeys($id, $columns);
        $fieldsInput = ['id' => $idMark['id']];
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
        $idData['code'] = $id['code'];
        $idMark = Common::makeMarkKeys($idData, $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $query = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($query);

        if (mysqli_num_rows($dataRanking) == 0) {
            return array('result' => false, 'message' => "0 rows");
        }

        $dataRanking = mysqli_fetch_assoc($dataRanking);
        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Notes';


        $query = Get::getAllData($rankingName);
        if (count($id) == 4){
            $values = array_values($id);
            $query .= " WHERE ".$values[2].' = '.$values[3];

        }

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

        //Create Database Connection
        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $columns = Common::showColumns('user');
        $fieldsMark += Common::makeMarkKeys($fields, $columns);

        //With ranking code, get ranking name and save ranking name in variable
        $fieldsInput = ['code' => $fieldsMark['code']];
        $query = GET::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        $ranking_nameNotes = "R_" . $data['ranking_name'] . '_Notes';
        $ranking_nameMain = "R_" . $data['ranking_name'];






        switch ($fields['type']) {
            case 'points':
                $fieldsUpdateRanking['points'] = $fields['amount'];
                break;
            case 'responsabilidad':
                $fieldsUpdateRanking['responsabilidad'] = $fields['amount'];
                $fields['task'] = 'null';
                break;
            case 'cooperacion':
                $fieldsUpdateRanking['cooperacion'] = $fields['amount'];
                $fields['task'] = 'null';
                break;
            case 'autonomia_e_iniciativa':
                $fieldsUpdateRanking['autonomia_e_iniciativa'] = $fields['amount'];
                $fields['task'] = 'null';
                break;
            case 'gestion_emocional':
                $fieldsUpdateRanking['gestion_emocional'] = $fields['amount'];
                $fields['task'] = 'null';
                break;
            case 'habilidades_de_pensamiento':
                $fieldsUpdateRanking['habilidades_de_pensamiento'] = $fields['amount'];
                $fields['task'] = 'null';
                break;
        }
        $fieldsUpdateRanking['code'] = $fields['code'];
        $fieldsUpdateRanking['id'] = $fields['id_valued'];

        $fieldsUpdateRankingMinus['code'] = $fields['code'];
        $fieldsUpdateRankingMinus['id'] = $fields['id_evaluator'];
        $fieldsUpdateRankingMinus['pointsSpend'] = -$fields['amount'];


        unset($fields['code']);



        $columns = Common::showColumns($ranking_nameNotes);
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $query = Insert::makeInsertQuery($ranking_nameNotes, $fieldsMark);
        $data = $database->getConnection()->query($query);
        


        if ($data) {
            $updateRanking = Ranking::update($fieldsUpdateRanking);

            if($fields['task'] = 'null'){
                $updateRanking = Ranking::update($fieldsUpdateRankingMinus);

            }


            return array('result' => true, 'message' => 'The insert has been made');
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

        $rankingName = "R_" . $data['ranking_name'].'_Notes';


        
        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Get::getDataField($rankingName, $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        $fieldsUpdateRanking['id'] = $data['id_evaluator'];
        $fieldsUpdateRanking['pointsSpend'] = $data['amount'];
        $fieldsUpdateRanking['code'] = $fields['code'];




        switch ($data['type']) {
            case 'points':
                $fieldsUpdateRankingMinus['pointsSpend'] = -$data['amount'];
                break;
            case 'responsabilidad':
                $fieldsUpdateRankingMinus['responsabilidad'] = -$data['amount'];
                break;
            case 'cooperacion':
                $fieldsUpdateRankingMinus['cooperacion'] = -$data['amount'];
                break;
            case 'autonomia_e_iniciativa':
                $fieldsUpdateRankingMinus['autonomia_e_iniciativa'] = -$data['amount'];
                break;
            case 'gestion_emocional':
                $fieldsUpdateRankingMinus['gestion_emocional'] = -$data['amount'];
                break;
            case 'habilidades_de_pensamiento':
                $fieldsUpdateRankingMinus['habilidades_de_pensamiento'] = -$data['amount'];
                break;
        }

        $fieldsUpdateRankingMinus['code'] = $fields['code'];
        $fieldsUpdateRankingMinus['id'] = $data['id_valued'];



        

        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Delete::deleteRow($rankingName, $fieldsInput);
        $data = $database->getConnection()->query($query);


        if ($data) {
            $updateRanking = Ranking::update($fieldsUpdateRanking);


            $updateRanking = Ranking::update($fieldsUpdateRankingMinus);



            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }

    }

    public static function update()
    {
    }
}

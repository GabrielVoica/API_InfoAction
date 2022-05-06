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
        $fieldsMark = Common::makeMarkKeys($id, $columns);


        $fieldsInput = ['code' => $fieldsMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Notes';

        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Get::getDataField($rankingName, $fieldsInput);
        $data = $database->getConnection()->query($query);
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
    }

    public static function update()
    {
    }
}

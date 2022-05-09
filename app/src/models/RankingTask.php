<?php

use Dotenv\Parser\Value;

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");

require_once("src/lib/Common.php");
require_once("src/lib/Create.php");
require_once("src/lib/Delete.php");
require_once("src/lib/Get.php");
require_once("src/lib/Insert.php");
require_once("src/lib/Update.php");
require_once("src/lib/Validator.php");






class RankingTask implements Model
{

    public static function get($id = null)
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

        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Task';

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
        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Task';


        $query = Get::getAllData($rankingName);
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


        foreach ($wishlist as $imagenull) {
            $imagenull["image"] = "paco";
        }


        return array('result' => true, 'message' => null, 'data' => $wishlist);
    }

    public static function insert(array $fields = null)
    {
        $database = new Database();
        $database->connect();



        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['code' => $fieldsMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $ranking_name = 'R_' . $dataRanking['ranking_name'] . '_Task';
        unset($fields['code']);

        $columns = Common::showColumns($ranking_name);
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        $query = Insert::makeInsertQuery($ranking_name, $fieldsMark);
        $data = $database->getConnection()->query($query);

        if ($data) {
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
        
        $fieldsInput = ['code' => $fieldsMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $ranking_name = 'R_' . $dataRanking['ranking_name'] . '_Task';
        unset($fields['code']);

        $fieldsInput = ['id' => $fieldsMark['id']];
        $query = Delete::deleteRow($ranking_name, $fieldsInput);
        $data = $database->getConnection()->query($query);


        if ($data) {
            return array('result' => false, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }

    }

    public static function update(array $fields = null)
    {

        $wherevalue = $fields['id'];
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('rankingdata');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        
        $fieldsInput = ['code' => $fieldsMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_Task';
        unset($fields['code']);
        unset($fields['id']);



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

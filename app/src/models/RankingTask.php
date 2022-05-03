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
        $idData['code'] = $id['id-ranking'];
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
        $idData['code'] = $id['id-ranking'];
        $idMark = Common::makeMarkKeys($idData, $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);

        if (mysqli_num_rows($dataRanking) == 0) {
            return array('result' => false, 'message' => "0 rows");
        }

        $dataRanking = mysqli_fetch_assoc($dataRanking);
        $rankingName = 'R_' . $dataRanking['ranking_name'] . '_History';


        $selectFields = array(0 => '*');
        $selectInnerFields = array(0 => 'image');
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
        $idMark = Common::makeMarkKeys($fields['code'], $columns);

        $fieldsInput = ['code' => $fields['code']];
        $query = GET::getDataField('rankingdata', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);
        $ranking_name = "R_" . $data['ranking_name'].'_Task';
    }

    public static function delete($fields)
    {
    }

    public static function update(array $fields = null)
    {
    }
}

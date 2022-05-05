<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


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

        $rankingName = 'R_' . $dataRanking['ranking_name'].'_Notes';

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
      

    }



    public static function delete($fields)
    {
       
    }

    public static function update()
    {
        
    
       
    }
}

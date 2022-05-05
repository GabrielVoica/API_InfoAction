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
        $idMark = Common::makeMarkKeys($id['code'], $columns);

        $fieldsInput = ['code' => $idMark['code']];
        $queryRanking = Get::getDataField('rankingdata', $fieldsInput);
        $dataRanking = $database->getConnection()->query($queryRanking);
        $dataRanking = mysqli_fetch_assoc($dataRanking);

        $rankingName = 'R_' . $dataRanking['ranking_name'].'_Notes';

        $columns = Common::showColumns($rankingName);
        $idMark = Common::makeMarkKeys($id, $columns);
        $fieldsInput = ['id' => $idMark['id-task']];
        $query = Get::getDataField($rankingName, $fieldsInput);
        print_r($query);
        $data = $database->getConnection()->query($query);


    }



    public static function getAll()
    {
      
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

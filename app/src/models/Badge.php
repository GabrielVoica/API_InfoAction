<?php

require_once("services/Database.php");
require_once("services/errors/NotFoundError.php");


require_once("src/lib/Insert.php");
require_once("src/lib/Validator.php");
require_once("src/lib/Common.php");
require_once("src/lib/Get.php");
require_once("src/lib/Delete.php");
require_once("src/lib/update.php");






class Badge implements Model
{

    public static function get($id = null, array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('badge');
        $idData['id'] = $id;

        $idMark = Common::makeMarkKeys($idData, $columns);
        $fieldsInput = ['id' => $idMark['id']];
        $query = Get::getDataField('badge', $fieldsInput);
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
        $query = Get::getAllData('badge');

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

        $columns = Common::showColumns('badge');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        $columnsRequired = Common::showRequiredColumns('badge');
        $columnsAll = Common::showColumnsWithoutID('badge');
        $fieldsKeys = array_keys($fields);



        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);
        }

        if (isset($fields['name'])) {

            $fields['name'] = str_replace("%20"," ",$fields['name']);

            if (!Validator::isText($fields['name'])) {
                return array('result' => false, 'message' => 'Name badge must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['name'], 'badge', 'name', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('badge', 'name', $fieldsMark['name'])) {
                return array('result' => false, 'message' => '' . $fields['name'] . ' exist, use another');
            }
        }

        if (isset($fields['description'])) {
            $fields['description'] = str_replace("%20"," ",$fields['description']);

            if (!Validator::isText($fields['description'])) {
                return array('result' => false, 'message' => 'Description badge must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['description'], 'badge', 'description', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
        }

        $columns = Common::showColumns('badge');
        $fieldsMark = Common::makeMarkKeys($fields,$columns);
        
        $query = Insert::makeInsertQuery('badge', $fieldsMark);
        $data = $database->getConnection()->query($query);

        if ($data) {
            return array('result' => true, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }

    }



    public static function delete($fields)
    {
        $database = new Database();
        $database->connect();



        if (count($fields) == 1) {
            $query = Delete::deleteRow($fields[0], null, null);

        } else {
            
            $columns = Common::showColumns('badge');
            $idData['id'] = $fields[1];
            $fieldsMark = Common::makeMarkKeys($idData, $columns);


            if (!Validator::isNumber($fields[1])) {
                return array('result' => false, 'message' => 'User Type : Only numbers');
            }

            $nameLenghtReturn = Validator::isLenght($fieldsMark['id'], 'badge', 'id', 1, 5);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }


            $queryidexist = Validator::isExist($fields[0], 'id', $fieldsMark['id']);
            if ($queryidexist >= 1) {
                return array('result' => false, 'message' =>  $queryidexist['message']);
            }


            $fieldsInput = ['id' => $fields[1]];
            $query = Delete::deleteRow($fields[0], $fieldsInput);
        }


        $data = $database->getConnection()->query($query);

        if ($data) {
            return array('result' => true, 'message' => null);
        } else {
            return array('result' => false, 'message' => null);
        }
    }

    

    public static function update(array $fields = null)
    {
        $database = new Database();
        $database->connect();


        $columns = Common::showColumns('badge');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['id' => $fields['id']];
        $query = Get::getDataField('badge',$fieldsInput);
        $data = $database->getConnection()->query($query);
        $data =  mysqli_fetch_assoc($data);


        if (!isset($fields['id'])) {
            return array('result' => false, 'message' =>  'ID field not exist');
        }


        $queryidexist = Validator::isExist('badge', 'id', $fields['id']);
        if ($queryidexist > 1) {
            return array('result' => false, 'message' =>  $queryidexist['message']);
        }

        if (isset($fields['name'])) {
            //Works
            if (!Validator::isText($fields['name'])) {
                return array('result' => false, 'message' => 'Ranking Name must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['name'], 'badge', 'name', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('badge', 'name', $fieldsMark['name'])) {
                return array('result' => false, 'message' => '' . $fields['name'] . ' exist, use another');
            }
        }

        if (isset($fields['description'])) {
            //Works
            $LenghtReturn = Validator::isLenght($fields['description'], 'badge', 'description', 4, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }
        }

        $columns = Common::showColumns('badge');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $query = Update::updateRow('badge', $fieldsMark,null,null);
        $data = $database->getConnection()->query($query);


        if ($data) {
            return array('result' => true, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

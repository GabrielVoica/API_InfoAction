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






class User implements Model
{

    public static function get($id = null)
    {

        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('user');
        $idData['id'] = $id;

        $idMark = Common::makeMarkKeys($idData, $columns);
        $fieldsInput = ['id' => $idMark['id']];
        $query = Get::getDataField('user', $fieldsInput);
        $data = $database->getConnection()->query($query);



        if ($data === false) {
            return array('result' => false, 'Error query select');
        }
        if (mysqli_num_rows($data) == 0) {
            return array('result' => false, 'message' => null);
        }
        $data = mysqli_fetch_assoc($data);

        $fieldsInput = ['id' => $idMark['id']];
        $query = Get::getDataField('rankingmembers', $fieldsInput);
        $dataranking = $database->getConnection()->query($query);


        while ($array = mysqli_fetch_assoc($dataranking)) {
            $data['code'][] = $array['code'];
        }


        return array('result' => true, 'message' => null, 'data' => $data);
    }


    public static function getAll()
    {
        $database = new Database();
        $database->connect();
        $query = Get::getAllData('user');

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

    public static function login(array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $columns = Common::showColumns('user');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['email' => $fieldsMark['email']];
        $query = Get::getDataField('user', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        if (isset($data['password']) && password_verify($fields['password'], $data['password'])) {
            return array('result' => true, 'message' => null, 'data' =>   ['id' => $data['id'], 'type' => $data['user_type']]);
        } else {
            return array('result' => false, 'message' => null,);
        }
    }

    public static function insert(array $fields = null)
    {
        $database = new Database();
        $database->connect();

        $confirmPasswd = $fields['conf_passwd'];
        unset($fields['conf_passwd']);

        $columns = Common::showColumns('user');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        $columnsRequired = Common::showRequiredColumns('user');
        $columnsAll = Common::showColumnsWithoutID('user');

        $fieldsKeys = array_keys($fields);


        $CheckFieldsInsert = Common::missingFieldsInsert($fieldsKeys, $columnsRequired, $columnsAll);
        if ($CheckFieldsInsert > 1) {
            return array('result' => false, 'message' => $CheckFieldsInsert['message']);
        }



        if (isset($fields['nick_name'])) {
            //Works
            if (!Validator::isText($fields['nick_name'])) {
                return array('result' => false, 'message' => 'Nickname must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['nick_name'], 'user', 'nick_name', 5, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('user', 'nick_name', $fieldsMark['nick_name'])) {
                return array('result' => false, 'message' => '' . $fields['nick_name'] . ' exist, use another');
            }
        }

        if (isset($fields['image'])) {

            if ($fields['image'] == 'image') {
                $fields['image'] = Common::getLink();
            }
            if ($fields['image'] == 'null') {
                $fields['image'] = "https://avatars.dicebear.com/api/bottts/" . $fields['nick_name'] . ".svg";
            }
        }

        if (isset($fields['email'])) {
            //Works
            if (!Validator::isEmail($fields['email'])) {
                return array('result' => false, 'message' => 'Email is not correct');
            }

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['email'], 'user', 'email', 10, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('user', 'email', $fieldsMark['email'])) {
                return array('result' => false, 'message' => '' . $fields['email'] . ' exist, use another');
            }
        }


        if (isset($fields['password'])) {

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['password'], 'user', 'password', 8, 30);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            if ($fields['password'] != $confirmPasswd) {
                return array('result' => false, 'message' => 'Password not coincide');
            }
            //Works
            $PasswordCheck = Validator::isPassword($fields['password']);
            if ($PasswordCheck > 1) {
                return array('result' => false, 'message' => $PasswordCheck['message']);
            } else {
                $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
            }
        } else {
            $nameLenghtReturn = Validator::isLenght($fields['password'], 'user', 'password', 8, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if (isset($fields['name'])) {
            //Works
            if (!Validator::isText($fields['name'])) {
                return array('result' => false, 'message' => 'Name must include only letters');
            }

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['name'], 'user', 'name', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if (isset($fields['lastname'])) {
            //Works
            if (!Validator::isText($fields['lastname'])) {
                return array('result' => false, 'message' => 'Lastname must include only letters');
            }
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['lastname'], 'user', 'lastname', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if (isset($fields['center'])) {
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['center'], 'user', 'center', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
         
            if (!Validator::isText($fields['center'])) {
                return array('result' => false, 'message' => 'Lastname must include only letters');
            }
        }

        if (isset($fields['rol'])) {
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['rol'], 'user', 'rol', 1, 1);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if (!Validator::isNumber($fields['rol'])) {
                return array('result' => false, 'message' => 'Rol : Only numbers');
            }

            //Works
            if ($fields['rol'] != 0 & $fields['rol'] != 1) {
                return array('result' => false, 'message' => 'Rol : Only 0 => Student or 1 => Teacher');
            }
        }


        if (isset($fields['user_type'])) {
            if (!Validator::isNumber($fields['user_type'])) {
                return array('result' => false, 'message' => 'User Type : Only numbers');
            }
            if ($fields['user_type'] != 0 && $fields['user_type'] != 1) {
                return array('result' => false, 'message' => 'Bad type user, 0 for User, 1 for Teacher');
            }
        }

        $columns = Common::showColumns('user');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);


        $query = Insert::makeInsertQuery('user', $fieldsMark);
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

            // $query = Delete::deleteRow('cookies', null);
            // $data = $database->getConnection()->query($query);

            // $query = Delete::deleteRow($fields[0], null, null);
        } else {

            $fieldsInput = ['user_id' => $fields[1]];
            $query = Delete::deleteRow('cookies', $fieldsInput);
            $data = $database->getConnection()->query($query);
            $columns = Common::showColumns('user');
            $idData['id'] = $fields[1];
            $fieldsMark = Common::makeMarkKeys($idData, $columns);


            if (!Validator::isNumber($fields[1])) {
                return array('result' => false, 'message' => 'User Type : Only numbers');
            }

            $nameLenghtReturn = Validator::isLenght($fieldsMark['id'], 'user', 'id', 1, 5);
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


        $columns = Common::showColumns('user');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);

        $fieldsInput = ['id' => $fields['id']];
        $query = Get::getDataField('user', $fieldsInput);
        $data = $database->getConnection()->query($query);
        $data =  mysqli_fetch_assoc($data);



        if (!isset($fields['id'])) {
            return array('result' => false, 'message' =>  'ID field not exist');
        }


        $queryidexist = Validator::isExist('user', 'id', $fields['id']);
        if ($queryidexist > 1) {
            return array('result' => false, 'message' =>  $queryidexist['message']);
        }


        if (isset($fields['nick_name'])) {
            //Works
            if (!Validator::isText($fields['nick_name'])) {
                return array('result' => false, 'message' => 'Nickname must include only letters');
            }

            //Works
            $LenghtReturn = Validator::isLenght($fields['nick_name'], 'user', 'nick_name', 5, null);
            if ($LenghtReturn > 1) {
                return array('result' => false, 'message' => $LenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('user', 'nick_name', $fieldsMark['nick_name'])) {
                return array('result' => false, 'message' => '' . $fields['nick_name'] . ' exist, use another');
            }
        }


        if (isset($fields['email'])) {
            //Works
            if (!Validator::isEmail($fields['email'])) {
                return array('result' => false, 'message' => 'Email is not correct');
            }

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['email'], 'user', 'email', 10, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            if (!Validator::isExist('user', 'email', $fieldsMark['email'])) {
                return array('result' => false, 'message' => '' . $fields['email'] . ' exist, use another');
            }
        }


        if (isset($fields['password'])) {


            //Works
            $nameLenghtReturn = Validator::isLenght($fields['password'], 'user', 'password', 8, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }

            //Works
            $PasswordCheck = Validator::isPassword($fields['password']);
            if ($PasswordCheck > 1) {
                return array('result' => false, 'message' => $PasswordCheck['message']);
            } else {
                $fields['password'] = password_hash($fields['password'], PASSWORD_DEFAULT);
            }
        }



        if (isset($fields['image'])) {

            $fieldsInput = ['id' => $fields['id']];
            $query = GET::getDataField('user', $fieldsInput);
            $data = $database->getConnection()->query($query);
            $data =  mysqli_fetch_assoc($data);
            $image = $data['image'];
            $imageFinal = substr($image, -35);
            $imageBot = substr($image, -4);


            if ($imageBot != ".svg") {
                $ruta = __DIR__ . "\..\user_pictures/";
                $delete = Delete::deleteFile($ruta, $imageFinal);
            }

            if ($fields['image'] == 'image') {
                $fields['image'] = Common::getLink();
            }

            if ($fields['image'] == "null") {
                $fields['image'] = "https://avatars.dicebear.com/api/bottts/" . $data['nick_name'] . ".svg";
            }
        }

        if (isset($fields['name'])) {
            //Works
            if (!Validator::isText($fields['name'])) {
                return array('result' => false, 'message' => 'Name must include only letters');
            }

            //Works
            $nameLenghtReturn = Validator::isLenght($fields['name'], 'user', 'name', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if (isset($fields['lastname'])) {
            //Works
            if (!Validator::isText($fields['lastname'])) {
                return array('result' => false, 'message' => 'Lastname must include only letters');
            }
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['lastname'], 'user', 'lastname', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
        }



        if (isset($fields['center'])) {
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['center'], 'user', 'center', 5, null);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            
            if (!Validator::isText($fields['center'])) {
                return array('result' => false, 'message' => 'Lastname must include only letters');
            }
            
        }

        if (isset($fields['rol'])) {
            //Works
            $nameLenghtReturn = Validator::isLenght($fields['rol'], 'user', 'rol', 1, 1);
            if ($nameLenghtReturn > 1) {
                return array('result' => false, 'message' => $nameLenghtReturn['message']);
            }
            //Works
            if (!Validator::isNumber($fields['rol'])) {
                return array('result' => false, 'message' => 'Rol : Only numbers');
            }

            //Works
            if ($fields['rol'] != 0 & $fields['rol'] != 1) {
                return array('result' => false, 'message' => 'Rol : Only 0 => Student or 1 => Teacher');
            }
        }




        if (isset($fields['user_type'])) {
            if (!Validator::isNumber($fields['user_type'])) {
                return array('result' => false, 'message' => 'User Type : Only numbers');
            }
            if ($fields['user_type'] != 0 && $fields['user_type'] != 1) {
                return array('result' => false, 'message' => 'Bad type user, 0 for User, 1 for Teacher');
            }
        }

        $wherevalue = $fieldsMark['id'];
        unset($fieldsMark['id']);
        
        $columns = Common::showColumns('user');
        $fieldsMark = Common::makeMarkKeys($fields, $columns);
        $query = Update::updateRow('user', $fieldsMark,'id',$wherevalue);
        $data = $database->getConnection()->query($query);




        if ($data) {
            $dat = User::get($fields['id']);


            for ($x = 0; $x < count($dat['data']['code']); $x++) {
                $columns = Common::showColumns('rankingmembers');
                $ranking_name['code'] = $dat['data']['code'][$x];
                $fieldsMark = Common::makeMarkKeys($ranking_name, $columns);
                $fieldsInput = ['code' => $fieldsMark['code']];
                $query = Get::getDataField('rankingdata', $fieldsInput);
                $dataranking = $database->getConnection()->query($query);
                while ($array = mysqli_fetch_assoc($dataranking)) {
                    $wishlist[] = $array;
                }
            }



            if (!isset($fields['name']) && isset($fields['lastname'])) {
                $fieldsRankingUpdate['name_lastname'] = $dat['name'] . ' ' . $fields['lastname'];
            }
            if (isset($fields['name']) && !isset($fields['lastname'])) {
                $fieldsRankingUpdate['name_lastname'] = $fields['name'] . ' ' . $dat['lastname'];
                print_r($fieldsRankingUpdate);

            }
            if (isset($fields['name']) && isset($fields['lastname'])) {
                $fieldsRankingUpdate['name_lastname'] = $fields['name'] . ' ' . $fields['lastname'];
            }


            if (isset($fields['nick_name'])){
            $fieldsRankingUpdate['nick_name'] = $fields['nick_name'];
         }
            $fieldsRankingUpdate['id'] = $fields['id'];



            for ($x = 0; $x < count($dat['data']['code']); $x++) {

                $ranking_name = "R_" . $wishlist[$x]['ranking_name'];
                $columns = Common::showColumns($ranking_name);
                $fieldsUpdateRanking =  Common::makeMarkKeys($fieldsRankingUpdate, $columns);
                $wherevalue = $fieldsUpdateRanking['id'];
                unset($fieldsUpdateRanking['id']);
    
                $query = Update::updateRow($ranking_name, $fieldsUpdateRanking,'id',$wherevalue);
                $data = $database->getConnection()->query($query);
            }
            return array('result' => true, 'message' => 'The insert has been made');
        } else {
            return array('result' => false, 'message' => 'The insert has not been made');
        }
    }
}

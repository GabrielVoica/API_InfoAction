<?php

use function PHPSTORM_META\type;

require_once("services/Database.php");


class User implements Model
{
    public static function get($id = null, array $fields = null)
    {
        if($fields === null){
        $query = "SELECT * FROM user WHERE id = $id";
        }
        else{
            $query = "SELECT * FROM user WHERE ";
            $keys = array_keys($fields);
            $values = array_values($fields);
            for($i = 0; $i < count($fields); $i++){
                if(count($fields) === 1){
                    $query = $query . "$keys[$i] = '$values[$i]'";
                }
                else{
                     $query = $query . "$keys[$i] = '$values[$i]' AND ";
                     if($i == count($fields) - 1){
                         $query = substr($query,0,strlen($query) - 5);
                     }
                }
            }
             
        }

        $database = new Database();

        $database->connect();

        $data = $database->getConnection()->query($query);

        $data = mysqli_fetch_assoc($data);


        return $data;
    }

    public static function getAll()
    {
        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query('SELECT * FROM user');
        $data = mysqli_fetch_assoc($data);

        return $data;
    }


    public static function insert(array $fields = null){

        $database = new Database();
        $database->connect();
        $columns_show = "SHOW COLUMNS FROM user";
        $types = $database->getConnection()->query($columns_show);
        $types = mysqli_fetch_all($types);


            $query = "INSERT INTO user (";

            $keys = array_keys($fields);
            $values = array_values($fields);
     
            for($i = 0; $i < count($fields); $i++){
                $query = "$query$keys[$i],";
            }

            $query = substr($query,0,-1);
            $query = $query.') ';
            $query = $query."Values(";


           //TODO funcion aparte

            for($x = 0; $x < count($types); $x++){
                for($y = 0; $y < count($fields); $y++){


                    if($keys[$y] == $types[$x][0] && !str_contains($types[$x][1], 'int')){
                        $values[$y] = "'$values[$y]'";
                    }
                }
            } 


            for($i = 0; $i < count($fields); $i++){
                    $query = "$query$values[$i],";
            }

            $query = substr($query,0,-1);
            $query = $query.");";


        $data = $database->getConnection()->query($query);

        if($data){
            return true;
        }
        else{
            return false;
        }

    }





    public static function delete($id)
    {
        
    }

    public static function deleteAll()
    {

    }

    public static function update($id, array $fields)
    {

    }
}
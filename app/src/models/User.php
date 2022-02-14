<?php


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

        
            $query = "INSERT INTO User";

            $keys = array_keys($fields);
            $values = array_values($fields);
     
            for($i = 0; $i < count($fields); $i++){
                $query = "$query ($keys[$i]) VALUES('$values[$i]') ";

                //$sql="INSERT INTO `daw2`.`usuario` VALUES ('$id','$user', '$hashed_password', '$email', sysdate(), sysdate(),'null',null,'$rol',0)";


                if($i == count($fields) - 1){
                    $query = $query . ")";
                }   
                
            }

        
        print_r($query);

        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query($query);
        $data = mysqli_fetch_assoc($data);

        return $data;
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
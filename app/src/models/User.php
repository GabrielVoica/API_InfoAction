<?php


require_once("services/Database.php");


class User implements Model
{
    public function get($id = null, array $fields = null)
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

    public function getAll()
    {
        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query('SELECT * FROM user');
        $data = mysqli_fetch_assoc($data);

        return $data;
    }


    public function insert(){
        
    }

    public function delete($id)
    {

    }

    public function deleteAll()
    {

    }

    public function update($id, array $fields)
    {

    }
}
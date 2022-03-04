<?php

require_once("services/Database.php");
require_once("services/Validator.php");
require_once("services/errors/NotFoundError.php");
require_once("services/Insert.php");


class Ranking implements Model
{

    public static function get($id = null, array $fields = null)
    {

    }



    public static function getField($id, $field)
    {
      
    }
  

    public static function getAll()
    {
     
    }


    public static function insert(array $fields = null)
    {

    }
       
        

    public static function delete($id)
    {
    }

    public static function deleteAll()
    {
    }

    public static function update(array $fields = null)
    {
    }
}

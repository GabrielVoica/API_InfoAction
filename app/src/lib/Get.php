<?php

require_once("services/Database.php");


class Get
{

    //Function get with specific field
    public static function getDataField($table, $value, $fields)
    {
        $database = new Database();
        $database->connect();

        $query = "SELECT * FROM $table WHERE $fields = $value";

        return $query;
    }

 



    public static function getAllData($table,$field ,$selectFields = null,$innerType = null, $innerTable = null, $innerField = null,$selectInnerFields = null){
        $database = new Database();
        $database->connect();

        

        $query = "SELECT * FROM $table";

        if($field != null || $selectFields != null || $innerType != null || $innerTable != null || $innerField != null || $selectInnerFields != null){

            $selectFieldsFinal = null;
            $selectInnerFieldsFinal = null;


            for ($x = 0; $x < count($selectFields); $x++) {
                $selectFieldsFinal .= "$table.$selectFields[$x],";

            }
            $selectFieldsFinal = substr($selectFieldsFinal, 0, -1);

            for ($x = 0; $x < count($selectInnerFields); $x++) {
                $selectInnerFieldsFinal .= "$innerTable.$selectInnerFields[$x],";
            }
            $selectInnerFieldsFinal = substr($selectInnerFieldsFinal, 0, -1);





            $query = "SELECT $selectFieldsFinal,$selectInnerFieldsFinal FROM $table ";


            $query .= "$innerType $innerTable ON $table.$field = $innerTable.$innerField";
        }

        return $query;
        
    }

}

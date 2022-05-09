<?php

require_once("services/Database.php");


class Get
{

    //Function get with specific field
    public static function getDataField($table, $value)
    {


        $keys = array_keys($value);
        $values = array_values($value);

        $query = "SELECT * FROM $table WHERE ";

        for ($x = 0; $x < count($keys); $x++) {
            $query .= "$keys[$x] = $values[$x] AND ";
        }
        $query = substr($query, 0, -4);

        return $query;
    }





    public static function getAllData($table, $field = null, $selectFields = null, $innerType = null, $innerTable = null, $innerField = null, $selectInnerFields = null, $fieldinput = null)
    {
        $database = new Database();
        $database->connect();

        if($fieldinput != null){
            $keys = array_keys($fieldinput);
            $values = array_values($fieldinput);
        }
     

        $query = "SELECT * FROM $table";

        if ($field != null && $selectFields != null && $innerType != null && $innerTable != null && $innerField != null && $selectInnerFields != null && $fieldinput != null) {

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

            if($fieldinput != null){
            $query .= " WHERE ";
            for ($x = 0; $x < count($keys); $x++) {
                $query .= "$keys[$x] = $values[$x] AND ";
            }
            $query = substr($query, 0, -4);}
        }

    

        return $query;
    }
}

<?php

require_once("Database.php");


Class Insert{

        public static function showColumns($table){
            $database = new Database();
            $database->connect();
            $columns_show = "SHOW COLUMNS FROM $table";
            
            $types = $database->getConnection()->query($columns_show);
            $types = mysqli_fetch_all($types);

            return $types;
        }


        public static function makInsertQuery($table,$fields,$types){

            $query = "INSERT INTO user (";

            $keys = array_keys($fields);
            $values = array_values($fields);
    
            for ($i = 0; $i < count($fields); $i++) {
                $query = "$query$keys[$i],";
            }
    
            $query = substr($query, 0, -1);
            $query = $query . ') ';
            $query = $query . "Values(";
    
    
            //TODO funcion aparte
    
            for ($x = 0; $x < count($types); $x++) {
                for ($y = 0; $y < count($fields); $y++) {
    
    
                    if ($keys[$y] == $types[$x][0] && !str_contains($types[$x][1], 'int')) {
                        $values[$y] = "'$values[$y]'";
                    }
                }
            }
    
    
            for ($i = 0; $i < count($fields); $i++) {
                $query = "$query$values[$i],";
            }
    
            $query = substr($query, 0, -1);
            $query = $query . ");";

            return $query;

        }

}
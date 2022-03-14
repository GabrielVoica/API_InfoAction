<?php

require_once("Database.php");

class TestFunction
{


    public static function updateRow($table,$fields,$types){
        $database = new Database();
        $database->connect();

       


    }


    public static function getUserRankingby($table){
        $database = new Database();
        $database->connect();
        $query = "SELECT user_name, points FROM $table order by points DESC";
       
    }


    public static function existID($table,$fields){
        $database = new Database();
        $database->connect();

    }
 
        public static function randomCode() {

            $n=8;
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
          
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
          
            return $randomString;
        }

        public static function selectUsersby() {
            
            $database = new Database();
            $database->connect();
        }
    
    
<?php

namespace App\Services\Database;


class Database{

    private $db_con = null;
    private $db_usr       = null;
    private $db_pass   = null;
    private $db_host   = null;
    private $db_database   = null;



    public function __construct(){
        $dotenv = \Dotenv\Dotenv::createImmutable("../..");
        $dotenv->load();

        $this->db_usr = getenv('DB_USER');
        $this->db_pass = getenv('DB_PASS');
        $this->db_host = getenv('DB_HOST');
        $this->db_database = getenv('DB_DATABASE');

    }

    public function connect(){
        $this->db_con = mysqli_connect($this->db_host,$this->db_usr,$this->db_pass,$this->db_database);
        if($this->db_con->error){
            echo $this->db_con->error;
        }
        else{
            return $this->db_con;
        }
    }

    public function getConnection(){
        return $this->db_con;
    }
}

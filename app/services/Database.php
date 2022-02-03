<?php

namespace App\Services;

/**
 * This class provides a database conexion instance
 * 
 */
class Database
{

    private $connection = null;
    private $user       = null;
    private $password   = null;
    private $hostname   = null;
    private $database   = null;


    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();

        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->hostname = $_ENV['DB_HOST'];
        $this->database = $_ENV['DB_DATABASE'];
    }

    public function connect()
    {
        $this->connection = mysqli_connect($this->hostname, $this->user, $this->password, $this->database);
        if ($this->connection->error) {
            echo $this->connection->error;
        } else {
            return $this->connection;
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

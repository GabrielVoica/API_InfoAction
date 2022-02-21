<?php

require_once('services/Database.php');

class CookieService
{

    private $cookieName;

    private $cookieValue;

    private $expiresAt;

    private $cookie = [];


    public function createCookie($cookieName, $cookieValue, $expiresAt)
    {
        $this->cookieName = $cookieName;
        $this->cookieValue = $cookieValue;
        $this->expiresAt = $expiresAt;
        $this->cookie = array('name' => $cookieName, 'value' => $cookieValue, 'expires_at' => $expiresAt);
    }

    public function setCookie()
    {
        setcookie($this->cookieName, $this->cookieValue, $this->expiresAt);
    }


    public static function createAuthCookie($userEmail, $userPassword)
    {
        $hash = hash('sha256', $userEmail . $userPassword);

        setcookie('SESSION_ID', $hash,time() + 360000);

        if(CookieService::checkAuthCookie($hash) === false){
            return false;
        }

        $query = "SELECT id FROM user WHERE email = '$userEmail'";

        $database = new Database();

        $database->connect();

        $data = $database->getConnection()->query($query);

        $data = mysqli_fetch_assoc($data);

        $id = $data['id'];

        $query = "INSERT INTO cookies (id,user_id) VALUES ('$hash',$id)";

        $data = $database->getConnection()->query($query);

        return true;
    }


    public static function checkAuthCookie($hash){
        $query = "SELECT * FROM cookies WHERE id = '$hash'";

        $database = new Database();
        $database->connect();
        $data = $database->getConnection()->query($query);

        if($data && mysqli_num_rows($data) !== 0){
            return false;
        }
        else{
            return true;
        }
    }
}

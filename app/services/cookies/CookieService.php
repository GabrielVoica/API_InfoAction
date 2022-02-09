<?php

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
}

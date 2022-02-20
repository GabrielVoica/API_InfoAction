<?php

require_once("src/models/User.php");
require_once("services/errors/NotFoundError.php");
require_once("services/responses/Response.php");
require_once("services/cookies/CookieService.php");

class LoginController implements Controller
{

    public function get($params)
    {
    }

    public function post($variables)
    {
        $result = User::login($variables['email'], $variables['password']);

        if ($result != false) {
            CookieService::createAuthCookie($variables['email'], $variables['password']);
            return Response::successful(); //Create cookie and store in the BackEndTea
        } else {
            return  NotFoundError::throw();
        }
    }

    public function put()
    {
    }

    public function delete()
    {
    }
}

<?php

require_once("src/models/User.php");
require_once("services/errors/NotFoundError.php");
require_once("services/responses/Response.php");
require_once("services/cookies/CookieService.php");
require_once('services/errors/BadRequestError.php');

class LoginController implements Controller
{

    public function get($params)
    {
    }

    public function post($variables)
    {
        $result = User::login($variables['email'], $variables['password']);

        if ($result['result'] == true) {
            $response = Response::successful();
            $response ['message'] = Response::successful()['message'];
            $response ['data'] = $result['data'];
            $cookie =  CookieService::createAuthCookie($variables['email'], $variables['password']);
            return $response;
        } else {
            return  NotFoundError::throw();
        }
    }

    public function put($variables)
    {
    }

    public function delete($variables)
    {
    }
}

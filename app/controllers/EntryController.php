<?php

require "services/cookies/CookieService.php";
require "services/errors/BadRequestError.php";
require "services/responses/Response.php";


class EntryController implements Controller
{
  private $cookieService;
  private $badRequestError;
  private $methodNotAllowedError;

  public function __construct()
  {
    $this->cookieService = new CookieService();
    $this->badRequestError = new BadRequestError();
  }

  public function get()
  {
  }


  public function post($variables)
  {

    if (!isset($variables['user_type'])) {
      return $this->badRequestError::throw();
    }

    if ($variables['user_type'] != 0 && $variables['user_type'] != 1) { //TODO create comprobation class
       return $this->badRequestError::throw();
    }

    //Student
    if ($variables['user_type'] == 0) {
      $this->cookieService->createCookie('user_type', 'student', time() + 1000000);
      $this->cookieService->setCookie();
    }
    //Teacher
    elseif ($variables['user_type'] == 1) {
      $this->cookieService->createCookie('user_type', 'teacher', time() + 1000000);
      $this->cookieService->setCookie();
    }

    return Response::successful();
  }

  public function put()
  {
  }

  public function delete()
  {
  }
}

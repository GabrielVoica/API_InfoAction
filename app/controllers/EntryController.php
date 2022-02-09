<?php

require "src/interface/Controller.php";
require "services/CookieService.php";


class EntryController implements Controller
{

    private $cookieService;

    public function __construct()
    {
        $this->cookieService = new CookieService();
    }

    public function get()
    {    
        
    }

    public function post($variables)
    {
       //Student
       if($variables['user_type'] == 0){
         $this->cookieService->createCookie('user_type','student',time()+1000000);
         $this->cookieService->setCookie();
       }
       //Teacher
       elseif($variables['user_type'] == 1){
         $this->cookieService->createCookie('user_type','teacher',time()+1000000);
         $this->cookieService->setCookie();
       }
    }

    public function put()
    {
    }

    public function delete()
    {
    }
}

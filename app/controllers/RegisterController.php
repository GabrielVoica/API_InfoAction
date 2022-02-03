<?php

namespace App\Controllers;

use App\Interface\Controller;

require "src/interface/Controller.php";


class RegisterController implements Controller
{

    public function get()
    {
        echo "Hello";
    }

    public function post()
    {
    }

    public function put()
    {
    }
}

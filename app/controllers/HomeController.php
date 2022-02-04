<?php



require "src/interface/Controller.php";



class HomeController implements Controller
{

    public function get()
    {
        echo "Get";
    }

    public function post()
    {
        echo "Post";
    }

    public function put()
    {
    }

    public function delete()
    {
    }
}

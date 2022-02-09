<?php

use Symfony\Component\Yaml\Yaml;

include "../vendor/autoload.php";

require "src/middleware/ProcessPetition.php";
require "services/petitions/BasePetition.php";
require "services/Database.php";
require "src/interface/Controller.php";
require "src/interface/HttpError.php";


/**
 * The yml php object that contains all the routes defined in the website
 * 
 */
$yamlLoader = Yaml::parse(file_get_contents("routes.yml"));

/**
 * The URI route added by the website user 
 * 
 * 
 */
$request = $_SERVER['REQUEST_URI'];


//TODO quitar en producción
$filtered_request = str_replace("/API_InfoAction/app", "", $request);

$request_params = array_filter(explode('/', $filtered_request));

$request_params_array = [];
$requestVariables = null;

foreach ($request_params as $param) {
    if (!str_contains($param, '?')) {
        array_push($request_params_array, $param);
    } else {
        $request_variables = explode('?', $param);

        array_push($request_params_array, $request_variables[0]);

        $requestVariables = $request_variables[1];
    }
}

if (count($request_params_array) == 0) {
    $request_params_array = array(0 => '/');
}


/**
 * Sets true if the route added by the user exists
 * 
 */
$existingRoute = false;


$controller = "";

//Checking if the route added by the user is present inside the routes file
foreach ($yamlLoader as $yamlRoute) {
    if ($yamlRoute['route'] == $request_params_array[0]) {
        $existingRoute = true;
        $controller =  $yamlRoute['controller'];
    }
}

$directory = scandir('./controllers');
$file_name = "";

foreach ($directory as $file) {
    if (str_contains($file, $controller) && $controller !== '') {
        $file_name = $file;
        require_once 'controllers/' . $file;
    }
}

$file_name = str_replace('.php', '', $file_name);

//Main controller instance
$controllerInstance = new $file_name(new Database());

$petition = new BasePetition($controllerInstance, $_SERVER['REQUEST_METHOD'], $requestVariables);

$petition->make();



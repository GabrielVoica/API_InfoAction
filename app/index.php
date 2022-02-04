<?php

use Symfony\Component\Yaml\Yaml;

include "../vendor/autoload.php";

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

foreach ($request_params as $param) {
    array_push($request_params_array, $param);
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
$controller_instance = new $file_name;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $controller_instance->get();
        break;
    case 'POST':
        $controller_instance->post();
        break;
    case 'PUT':
        $controller_instance->put();
        break;
    case 'DELETE':
        $controller_instance->delete();
        break;
}
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
$request = str_replace("/api/app", "", $request);


/**
 * Sets true if the route added by the user exists
 * 
 */
$existingRoute = false;


//Checking if the route added by the user is present inside the routes file
foreach ($yamlLoader as $yamlRoute) {
    if ($yamlRoute['route'] == $request) {
        $existingRoute = true;
        include $yamlRoute['controller'];
    }
}

//If the route doesn't exist the RouteNotFoundController is called
/*if (!$existingRoute) { TODO
    include "controllers/RouteNotFoundController.php";
}*/
<?php

/**
 * 
 */
class ProcessPeticion{

   public function __construct(){

   }

   /**
    * 
    */
   public function process(){
      header('Access-Control-Allow-Origin: http://localhost:4200');
      header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
      header('Access-Control-Allow-Credentials: true');
      header("Allow: GET, POST, OPTIONS, PUT, DELETE");
   }


   public function addCoockie(){

   }
}